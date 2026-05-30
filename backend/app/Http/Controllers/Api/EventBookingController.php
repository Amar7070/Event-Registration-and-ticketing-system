<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Review;
use App\Models\CopyrightReport;
use App\Services\TicketService;
use App\Services\WaitlistService;
use App\Services\QRCodeService;
use App\Services\DynamicPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class EventBookingController extends Controller
{
    protected TicketService $ticketService;
    protected WaitlistService $waitlistService;

    public function __construct(TicketService $ticketService, WaitlistService $waitlistService)
    {
        $this->ticketService = $ticketService;
        $this->waitlistService = $waitlistService;
    }

    public function validateCoupon(Request $request, Event $event)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
            if (!$coupon || !$coupon->is_active) {
                return response()->json(['valid' => false, 'message' => 'Invalid or inactive coupon code.'], 422);
            }

            $now = now();
            if ($coupon->valid_from && $coupon->valid_from->isFuture()) {
                return response()->json(['valid' => false, 'message' => 'This coupon is not active yet.'], 422);
            }
            if ($coupon->valid_until && $coupon->valid_until->isPast()) {
                return response()->json(['valid' => false, 'message' => 'This coupon has expired.'], 422);
            }

            if ($coupon->event_id !== null && $coupon->event_id !== $event->id) {
                return response()->json(['valid' => false, 'message' => 'This coupon is not valid for this event.'], 422);
            }
            if ($coupon->event_id === null && $coupon->organizer_id !== $event->organizer_id) {
                return response()->json(['valid' => false, 'message' => 'This coupon is not valid for this event.'], 422);
            }

            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                return response()->json(['valid' => false, 'message' => 'This coupon has reached its maximum usage limit.'], 422);
            }

            $ticketType = TicketType::findOrFail($request->ticket_type_id);
            $currentUnitPrice = app(DynamicPricingService::class)->getCurrentPrice($ticketType);
            $subtotal = $currentUnitPrice * $request->quantity;

            if ($subtotal < $coupon->min_order_amount) {
                return response()->json(['valid' => false, 'message' => 'Minimum order amount of ₹' . number_format($coupon->min_order_amount) . ' is required to use this coupon.'], 422);
            }

            $userUsage = Ticket::where('user_id', Auth::id())
                ->where('coupon_id', $coupon->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->count();
            if ($userUsage >= $coupon->usage_per_user) {
                return response()->json(['valid' => false, 'message' => 'You have already used this coupon the maximum allowed times.'], 422);
            }

            $discountAmount = 0;
            if ($coupon->type === 'percentage') {
                $discountAmount = $subtotal * ($coupon->value / 100);
                if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                    $discountAmount = $coupon->max_discount;
                }
            } else {
                $discountAmount = $coupon->value;
            }

            $totalAmount = max(0, $subtotal - $discountAmount);

            return response()->json([
                'valid' => true,
                'coupon_id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => $discountAmount,
                'subtotal' => $subtotal,
                'total' => $totalAmount,
                'message' => 'Coupon applied! You saved ₹' . number_format($discountAmount, 2)
            ]);

        } catch (Exception $e) {
            return response()->json(['valid' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function book(Request $request, Event $event)
    {
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1|max:10',
            'attendee_details' => 'nullable|array',
            'coupon_code' => 'nullable|string'
        ]);

        try {
            $ticket = $this->ticketService->bookTicket($request, $event);

            if ($ticket->total_amount > 0) {
                return response()->json([
                    'requires_payment' => true,
                    'payment_id' => $ticket->payment_id,
                    'amount' => (float) $ticket->total_amount,
                ]);
            }

            // Free ticket booking
            $ticket->update(['status' => 'confirmed']);
            $ticket->payment()->update(['status' => 'completed']);
            
            try {
                app(QRCodeService::class)->generate($ticket);
            } catch (Exception $e) {
                \Illuminate\Support\Facades\Log::error('QR code generation failed in API book: ' . $e->getMessage());
            }

            return response()->json([
                'requires_payment' => false,
                'booking_reference' => $ticket->booking_reference,
                'message' => 'Free ticket booked successfully.'
            ]);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function addReview(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $hasTicket = $event->tickets()->where('user_id', Auth::id())->where('status', 'confirmed')->exists();
        if (!$hasTicket) {
            return response()->json(['message' => 'You can only review events you have attended.'], 403);
        }

        if ($event->end_date > now()) {
            return response()->json(['message' => 'You can only leave a review after the event has ended.'], 400);
        }

        $review = Review::updateOrCreate(
            ['user_id' => Auth::id(), 'event_id' => $event->id],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => false
            ]
        );

        return response()->json([
            'message' => 'Your review has been submitted and is pending approval.',
            'review' => $review
        ]);
    }

    public function reportViolation(Request $request, Event $event)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'evidence_url' => 'nullable|url|max:2000',
        ]);

        $report = CopyrightReport::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'evidence_url' => $validated['evidence_url'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Thank you. Your security/copyright report has been registered. Our ecosystem stewards will audit this architecture immediately.',
            'report' => $report
        ]);
    }

    public function joinWaitlist(Request $request, Event $event)
    {
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        $ticketType = TicketType::findOrFail($request->ticket_type_id);

        try {
            $this->waitlistService->join(Auth::user(), $event, $ticketType);
            $position = Auth::user()->waitlists()->where('event_id', $event->id)->first()->position;
            return response()->json([
                'message' => 'You have been added to the waitlist at position ' . $position,
                'position' => $position
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function showTicket($reference)
    {
        $ticket = Ticket::with(['event.venue', 'event.category', 'ticketType', 'payment'])
            ->where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Auto-heal QR code
        if ($ticket->status === 'confirmed' && (empty($ticket->qr_code_path) || !file_exists(storage_path('app/public/' . $ticket->qr_code_path)))) {
            try {
                app(QRCodeService::class)->generate($ticket);
                $ticket->refresh();
            } catch (Exception $e) {
                \Illuminate\Support\Facades\Log::error('QR code generation failed in showTicket API: ' . $e->getMessage());
            }
        }

        return response()->json([
            'id' => $ticket->id,
            'booking_reference' => $ticket->booking_reference,
            'quantity' => $ticket->quantity,
            'status' => $ticket->status,
            'unit_price' => (float) $ticket->unit_price,
            'discount_amount' => (float) $ticket->discount_amount,
            'tax_amount' => (float) $ticket->tax_amount,
            'total_amount' => (float) $ticket->total_amount,
            'qr_code_url' => $ticket->qr_code_path ? asset('storage/' . $ticket->qr_code_path) : null,
            'qr_token' => $ticket->qr_token,
            'checked_in_at' => $ticket->checked_in_at ? $ticket->checked_in_at->toIso8601String() : null,
            'purchased_at' => $ticket->created_at->toIso8601String(),
            'event' => [
                'id' => $ticket->event->id,
                'title' => $ticket->event->title,
                'slug' => $ticket->event->slug,
                'start_date' => $ticket->event->start_date->toIso8601String(),
                'end_date' => $ticket->event->end_date->toIso8601String(),
                'banner' => $ticket->event->banner,
                'category' => $ticket->event->category->name ?? null,
                'venue' => $ticket->event->venue ? $ticket->event->venue->name : 'Digital Portal',
                'city' => $ticket->event->venue ? $ticket->event->venue->city : 'Online',
            ],
            'ticket_type' => [
                'name' => $ticket->ticketType->name,
                'is_transferable' => $ticket->ticketType->is_transferable,
                'is_refundable' => $ticket->ticketType->is_refundable,
            ],
            'download_url' => route('user.tickets.download', $ticket->id)
        ]);
    }

    public function transferTicket(Request $request, $reference)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $ticket = Ticket::where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $recipient = \App\Models\User::where('email', $request->email)->first();

        if ($recipient->id === Auth::id()) {
            return response()->json(['message' => 'You cannot transfer a ticket to yourself.'], 422);
        }

        try {
            $newTicket = $this->ticketService->transferTicket($ticket, $recipient);
            return response()->json([
                'message' => 'Ticket transferred successfully.',
                'new_ticket_reference' => $newTicket->booking_reference
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function downloadTicket(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf', compact('ticket'));
        return $pdf->download("ticket-{$ticket->booking_reference}.pdf");
    }
}
