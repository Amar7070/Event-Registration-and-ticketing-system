<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show the checkout page for a payment.
     */
    public function checkout(Payment $payment)
    {
        // If the payment belongs to the logged-in user but is already completed,
        // redirect them to their ticket instead of showing a 403 Forbidden error.
        if ($payment->user_id === auth()->id() && $payment->status === 'completed') {
            $ticket = \App\Models\Ticket::where('payment_id', $payment->id)->first();
            if ($ticket) {
                return redirect()->route('user.tickets.show', $ticket->booking_reference)
                    ->with('success', 'This ticket has already been successfully purchased.');
            }
            return redirect()->route('user.tickets.index')
                ->with('success', 'This ticket has already been successfully purchased.');
        }

        // Security check
        if ($payment->user_id !== auth()->id() || $payment->status !== 'pending') {
            abort(403);
        }

        return view('user.payments.checkout', compact('payment'));
    }

    /**
     * Process the payment (Mock logic for now, or actual integration).
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'gateway' => 'required|in:stripe,razorpay',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        $payment->update([
            'gateway_payment_id' => 'G_PAY_' . strtoupper(\Illuminate\Support\Str::random(10)),
            'payment_method' => $request->gateway,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        \App\Models\Ticket::where('payment_id', $payment->id)->update(['status' => 'confirmed']);

        $ticket = \App\Models\Ticket::where('payment_id', $payment->id)->first();
        if ($ticket) {
            try {
                app(\App\Services\QRCodeService::class)->generate($ticket);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('QR code generation failed on payment completion: ' . $e->getMessage());
            }
        }

        if (!\App\Models\Invoice::where('payment_id', $payment->id)->exists()) {
            app(\App\Services\InvoiceService::class)->generate($payment);
        }

        event(new \App\Events\PaymentCompleted($payment));

        return response()->json([
            'success' => true,
            'message' => 'Payment completed successfully.',
            'payment_id' => $payment->id,
            'booking_reference' => $ticket ? $ticket->booking_reference : null,
        ]);
    }
}
