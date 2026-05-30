<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Exception;

class BookingController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Show the booking form.
     */
    public function create(Event $event)
    {
        // Must be published to book
        if ($event->status !== 'published') {
            abort(404);
        }

        $event->load(['ticketTypes' => function ($query) {
            $query->where('is_active', true);
        }]);

        // Fetch coupons valid for this event or overall coupons from this organizer
        $availableCoupons = \App\Models\Coupon::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>', now())
            ->where(function ($query) use ($event) {
                $query->where('event_id', $event->id)
                      ->orWhere(function ($q) use ($event) {
                          $q->whereNull('event_id')
                            ->where('organizer_id', $event->organizer_id);
                      });
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->get();

        return view('user.booking.create', compact('event', 'availableCoupons'));
    }

    /**
     * Validate coupon asynchronously.
     */
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

            // Date Validity Check
            $now = now();
            if ($coupon->valid_from && $coupon->valid_from->isFuture()) {
                return response()->json(['valid' => false, 'message' => 'This coupon is not active yet.'], 422);
            }
            if ($coupon->valid_until && $coupon->valid_until->isPast()) {
                return response()->json(['valid' => false, 'message' => 'This coupon has expired.'], 422);
            }

            // Event and Organizer Match
            if ($coupon->event_id !== null && $coupon->event_id !== $event->id) {
                return response()->json(['valid' => false, 'message' => 'This coupon is not valid for this event.'], 422);
            }
            if ($coupon->event_id === null && $coupon->organizer_id !== $event->organizer_id) {
                return response()->json(['valid' => false, 'message' => 'This coupon is not valid for this event.'], 422);
            }

            // Total Usage Limit
            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                return response()->json(['valid' => false, 'message' => 'This coupon has reached its maximum usage limit.'], 422);
            }

            // Calculate Subtotal
            $ticketType = \App\Models\TicketType::findOrFail($request->ticket_type_id);
            $currentUnitPrice = app(\App\Services\DynamicPricingService::class)->getCurrentPrice($ticketType);
            $subtotal = $currentUnitPrice * $request->quantity;

            // Minimum Order Amount Check
            if ($subtotal < $coupon->min_order_amount) {
                return response()->json(['valid' => false, 'message' => 'Minimum order amount of ₹' . number_format($coupon->min_order_amount) . ' is required to use this coupon.'], 422);
            }

            // User Specific Usage Limit
            $userUsage = Ticket::where('user_id', auth()->id())
                ->where('coupon_id', $coupon->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->count();
            if ($userUsage >= $coupon->usage_per_user) {
                return response()->json(['valid' => false, 'message' => 'You have already used this coupon the maximum allowed times.'], 422);
            }

            // Calculate Discount
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

        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store the ticket booking.
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1|max:10',
            'attendee_details' => 'nullable|array',
            'coupon_code' => 'nullable|string'
        ]);

        try {
            $ticket = $this->ticketService->bookTicket($request, $event);

            // Redirect to payment gateway or success page if free
            if ($ticket->total_amount > 0) {
                return redirect()->route('payments.checkout', $ticket->payment_id)
                    ->with('success', 'Ticket booked successfully. Please complete the payment.');
            }

            // If free ticket
            $ticket->update(['status' => 'confirmed']);
            $ticket->payment()->update(['status' => 'completed']);
            
            return redirect()->route('user.tickets.show', $ticket->booking_reference)
                ->with('success', 'Ticket booked successfully.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * List user's tickets.
     */
    public function index()
    {
        $tickets = Ticket::with(['event', 'ticketType'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.tickets.index', compact('tickets'));
    }

    /**
     * Show a specific ticket.
     */
    public function show($reference)
    {
        $ticket = Ticket::with(['event', 'ticketType', 'payment'])
            ->where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Auto-heal/generate QR code if ticket is confirmed but QR code path is empty
        if ($ticket->status === 'confirmed' && (empty($ticket->qr_code_path) || !file_exists(storage_path('app/public/' . $ticket->qr_code_path)))) {
            try {
                app(\App\Services\QRCodeService::class)->generate($ticket);
                $ticket->refresh();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('QR code generation failed in show: ' . $e->getMessage());
            }
        }

        return view('user.tickets.show', compact('ticket'));
    }

    /**
     * Transfer ticket.
     */
    public function transfer(Request $request, $reference)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $ticket = Ticket::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $recipient = \App\Models\User::where('email', $request->email)->first();

        if ($recipient->id === auth()->id()) {
            return back()->with('error', 'You cannot transfer a ticket to yourself.');
        }

        try {
            $newTicket = $this->ticketService->transferTicket($ticket, $recipient);
            return redirect()->route('user.tickets.index')->with('success', 'Ticket transferred successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
