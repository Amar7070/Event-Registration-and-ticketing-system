<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Refund;

class PaymentService
{
    // STRIPE
    public function createStripePaymentIntent(float $amount, string $currency): array
    {
        $stripeSecret = config('payment.stripe.secret', config('services.stripe.secret'));
        \Stripe\Stripe::setApiKey($stripeSecret);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => (int) round($amount * 100),
            'currency' => strtolower($currency),
            'metadata' => ['platform' => 'SmartEvent'],
        ]);
        return ['client_secret' => $intent->client_secret, 'intent_id' => $intent->id];
    }

    // RAZORPAY
    public function createRazorpayOrder(float $amount, string $currency): array
    {
        $razorpayKey = config('payment.razorpay.key', config('services.razorpay.key'));
        $razorpaySecret = config('payment.razorpay.secret', config('services.razorpay.secret'));

        $api = new \Razorpay\Api\Api($razorpayKey, $razorpaySecret);
        $order = $api->order->create([
            'amount' => (int) round($amount * 100),
            'currency' => $currency,
            'payment_capture' => 1,
        ]);
        return ['order_id' => $order->id, 'key' => $razorpayKey];
    }

    // Platform Commission Calculation
    public function calculateCommission(float $amount): array
    {
        $commissionRate = config('app.platform_commission_percent', 5) / 100;
        $platformFee = round($amount * $commissionRate, 2);
        $taxRate = 0.18; // 18% GST on platform fee
        $taxOnFee = round($platformFee * $taxRate, 2);
        $organizerEarnings = $amount - $platformFee - $taxOnFee;
        return compact('platformFee', 'taxOnFee', 'organizerEarnings');
    }

    // Refund Processing
    public function processRefund(Refund $refund): bool 
    { 
        if ($refund->payment->gateway === 'stripe') {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $stripeRefund = \Stripe\Refund::create([
                'payment_intent' => $refund->payment->gateway_payment_id,
                'amount' => $refund->amount * 100,
            ]);
            $refund->update([
                'status' => 'processed',
                'gateway_refund_id' => $stripeRefund->id,
                'processed_at' => now(),
            ]);
            return true;
        } elseif ($refund->payment->gateway === 'razorpay') {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $razorpayRefund = $api->refund->create([
                'payment_id' => $refund->payment->gateway_payment_id,
                'amount' => $refund->amount * 100, // amount in paise
            ]);
            $refund->update([
                'status' => 'processed',
                'gateway_refund_id' => $razorpayRefund->id,
                'processed_at' => now(),
            ]);
            return true;
        }

        return false;
    }
}
