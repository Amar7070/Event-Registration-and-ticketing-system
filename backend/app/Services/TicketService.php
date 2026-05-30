<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Exception;

class TicketService
{
    protected DynamicPricingService $dynamicPricingService;

    public function __construct(DynamicPricingService $dynamicPricingService)
    {
        $this->dynamicPricingService = $dynamicPricingService;
    }

    /**
     * Book a ticket for an event.
     * 
     * @param Request $request
     * @param Event $event
     * @return Ticket
     * @throws Exception
     */
    public function bookTicket(Request $request, Event $event): Ticket
    {
        if ($event->status !== 'published') {
            throw new Exception('Event is not published.');
        }

        $ticketType = TicketType::findOrFail($request->ticket_type_id);
        if ($ticketType->quantity_sold + $request->quantity > $ticketType->quantity_total) {
            throw new Exception('Not enough tickets available.');
        }

        $userTicketCount = Ticket::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->where('ticket_type_id', $ticketType->id)
            ->sum('quantity');

        if (($userTicketCount + $request->quantity) > $ticketType->max_per_order) {
            throw new Exception('You have exceeded the maximum tickets allowed per order.');
        }

        $currentUnitPrice = $this->dynamicPricingService->getCurrentPrice($ticketType);

        $coupon = null;
        $discountAmount = 0;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if (!$coupon || !$coupon->is_active) {
                throw new Exception('Invalid or inactive coupon code.');
            }

            // Date Validity Check
            $now = now();
            if ($coupon->valid_from && $coupon->valid_from->isFuture()) {
                throw new Exception('This coupon is not active yet.');
            }
            if ($coupon->valid_until && $coupon->valid_until->isPast()) {
                throw new Exception('This coupon has expired.');
            }

            // Event and Organizer Match
            if ($coupon->event_id !== null && $coupon->event_id !== $event->id) {
                throw new Exception('This coupon is not valid for this event.');
            }
            if ($coupon->event_id === null && $coupon->organizer_id !== $event->organizer_id) {
                throw new Exception('This coupon is not valid for this event.');
            }

            // Total Usage Limit
            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                throw new Exception('This coupon has reached its maximum usage limit.');
            }

            // Minimum Order Amount Check
            $subtotal = $currentUnitPrice * $request->quantity;
            if ($subtotal < $coupon->min_order_amount) {
                throw new Exception('Minimum order amount of ₹' . number_format($coupon->min_order_amount) . ' is required to use this coupon.');
            }

            // User Specific Usage Limit
            $userUsage = Ticket::where('user_id', auth()->id())
                ->where('coupon_id', $coupon->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->count();
            if ($userUsage >= $coupon->usage_per_user) {
                throw new Exception('You have already used this coupon the maximum allowed times.');
            }
        }

        $subtotal = $currentUnitPrice * $request->quantity;

        if ($coupon) {
            if ($coupon->type === 'percentage') {
                $discountAmount = $subtotal * ($coupon->value / 100);
                if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                    $discountAmount = $coupon->max_discount;
                }
            } else {
                $discountAmount = $coupon->value;
            }
        }

        $totalAmount = max(0, $subtotal - $discountAmount);

        $commissionData = app(\App\Services\PaymentService::class)->calculateCommission($totalAmount);

        $payment = new \App\Models\Payment();
        $payment->user_id = auth()->id() ?? 1;
        $payment->event_id = $event->id;
        $payment->payment_reference = 'PAY-' . date('Y') . '-' . strtoupper(Str::random(8));
        $payment->amount = $totalAmount;
        $payment->tax_amount = 0;
        $payment->platform_fee = $commissionData['platformFee'];
        $payment->organizer_earnings = $commissionData['organizerEarnings'];
        $payment->currency = config('payment.currency', 'INR');
        $payment->status = 'pending';
        $payment->save();

        $ticket = new Ticket();
        $ticket->booking_reference = 'SE-' . date('Y') . '-' . strtoupper(Str::random(6));
        $ticket->event_id = $event->id;
        $ticket->ticket_type_id = $ticketType->id;
        $ticket->user_id = auth()->id() ?? 1;
        $ticket->payment_id = $payment->id;
        $ticket->quantity = $request->quantity;
        $ticket->unit_price = $currentUnitPrice;
        $ticket->discount_amount = $discountAmount;
        $ticket->tax_amount = 0;
        $ticket->total_amount = $totalAmount;
        $ticket->status = 'pending';
        $ticket->qr_token = \Illuminate\Support\Str::random(32);
        $ticket->attendee_details = $request->attendee_details;

        if ($coupon) {
            $ticket->coupon_id = $coupon->id;
            $coupon->increment('used_count');
        }

        if (session()->has('referral_code')) {
            $ticket->referral_code = session('referral_code');
        }

        $ticket->save();

        $ticketType->increment('quantity_sold', $request->quantity);
        $event->increment('registered_count', $request->quantity);

        event(new \App\Events\TicketBooked($ticket));

        return $ticket;
    }

    /**
     * Transfer a ticket to another user.
     * 
     * @param Ticket $ticket
     * @param \App\Models\User $recipient
     * @return Ticket
     */
    public function transferTicket(Ticket $ticket, \App\Models\User $recipient): Ticket
    {
        if (!$ticket->ticketType->is_transferable) {
            throw new Exception('This ticket type is not transferable.');
        }

        if ($ticket->status !== 'confirmed') {
            throw new Exception('Only confirmed tickets can be transferred.');
        }

        // Mark old ticket as transferred
        $ticket->update([
            'is_transferred' => true,
            'transferred_to' => $recipient->id,
            'status' => 'cancelled', // Or keep confirmed but transferred flag is set
        ]);

        // Create new ticket for recipient
        $newTicket = $ticket->replicate(['is_transferred', 'transferred_to', 'checked_in_at', 'qr_token', 'booking_reference']);
        $newTicket->user_id = $recipient->id;
        $newTicket->qr_token = Str::random(32);
        $newTicket->booking_reference = 'SE-' . date('Y') . '-' . strtoupper(Str::random(6));
        $newTicket->save();

        return $newTicket;
    }

    /**
     * Upgrade a ticket to a new ticket type.
     * 
     * @param Ticket $ticket
     * @param TicketType $newTicketType
     * @return Ticket
     */
    public function upgradeTicket(Ticket $ticket, TicketType $newTicketType): Ticket
    {
        if ($newTicketType->price <= $ticket->unit_price) {
            throw new Exception('Can only upgrade to a higher priced ticket.');
        }

        if ($newTicketType->quantity_sold >= $newTicketType->quantity_total) {
            throw new Exception('New ticket type is sold out.');
        }

        $priceDifference = $newTicketType->price - $ticket->unit_price;

        // Cancel old ticket
        $ticket->update(['status' => 'cancelled']);
        $ticket->ticketType->decrement('quantity_sold', $ticket->quantity);

        // Create new ticket (pending payment for the difference)
        $newTicket = $ticket->replicate(['qr_token', 'booking_reference', 'status', 'ticket_type_id', 'unit_price', 'total_amount']);
        $newTicket->ticket_type_id = $newTicketType->id;
        $newTicket->unit_price = $newTicketType->price;
        $newTicket->total_amount = $newTicketType->price * $newTicket->quantity;
        $newTicket->qr_token = Str::random(32);
        $newTicket->booking_reference = 'SE-' . date('Y') . '-' . strtoupper(Str::random(6));
        $newTicket->status = 'pending';
        $newTicket->save();

        $newTicketType->increment('quantity_sold', $newTicket->quantity);

        return $newTicket;
    }
}
