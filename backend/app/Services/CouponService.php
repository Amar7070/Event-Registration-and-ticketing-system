<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Http\Request;
use Exception;

class CouponService
{
    /**
     * Validate a coupon for an event.
     *
     * @param string $code
     * @param Event $event
     * @param float $orderAmount
     * @return Coupon
     * @throws Exception
     */
    public function validateCoupon(string $code, Event $event, float $orderAmount): Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw new Exception('Invalid coupon code.');
        }

        if (!$coupon->is_active) {
            throw new Exception('This coupon is no longer active.');
        }

        if (now()->lt($coupon->valid_from) || now()->gt($coupon->valid_until)) {
            throw new Exception('This coupon has expired or is not yet valid.');
        }

        if ($coupon->event_id !== null && $coupon->event_id !== $event->id) {
            throw new Exception('This coupon is not valid for this event.');
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            throw new Exception('This coupon has reached its usage limit.');
        }

        if ($orderAmount < $coupon->min_order_amount) {
            throw new Exception("Minimum order amount of {$coupon->min_order_amount} required.");
        }

        // Additional checks like usage_per_user can be added here
        
        return $coupon;
    }
}
