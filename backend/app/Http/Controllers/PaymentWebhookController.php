<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handleStripe(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->updatePaymentStatus($paymentIntent->id, 'completed');
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->updatePaymentStatus($paymentIntent->id, 'failed');
                break;
            default:
                Log::info("Unhandled event type: {$event->type}");
        }

        return response()->json(['status' => 'success']);
    }

    public function handleRazorpay(Request $request)
    {
        // Add Razorpay signature validation logic here
        $webhookSecret = config('services.razorpay.webhook_secret');
        $webhookSignature = $request->header('X-Razorpay-Signature');
        
        // Very basic validation logic (use official SDK in prod)
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);
        
        if (!hash_equals($expectedSignature, $webhookSignature)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = $request->all();

        if ($payload['event'] === 'payment.captured') {
            $this->updatePaymentStatus($payload['payload']['payment']['entity']['id'], 'completed');
        } elseif ($payload['event'] === 'payment.failed') {
            $this->updatePaymentStatus($payload['payload']['payment']['entity']['id'], 'failed');
        }

        return response()->json(['status' => 'success']);
    }

    private function updatePaymentStatus($gatewayPaymentId, $status)
    {
        $payment = Payment::where('gateway_payment_id', $gatewayPaymentId)->first();
        if ($payment) {
            $payment->update([
                'status' => $status,
                'paid_at' => $status === 'completed' ? now() : null,
            ]);
            
            if ($status === 'completed') {
                // Generate Invoice
                app(\App\Services\InvoiceService::class)->generate($payment);
                
                // Update related tickets to confirmed
                \App\Models\Ticket::where('payment_id', $payment->id)->update([
                    'status' => 'confirmed'
                ]);
                
                // Trigger event for emails and QR generation
                event(new \App\Events\PaymentCompleted($payment));
            } elseif ($status === 'failed') {
                // Handle failure: Cancel tickets and release inventory
                $tickets = \App\Models\Ticket::where('payment_id', $payment->id)->get();
                foreach($tickets as $ticket) {
                    $ticket->update(['status' => 'cancelled']);
                    $ticket->ticketType->decrement('quantity_sold', $ticket->quantity);
                    $payment->event->decrement('registered_count', $ticket->quantity);
                }
            }
        }
    }
}
