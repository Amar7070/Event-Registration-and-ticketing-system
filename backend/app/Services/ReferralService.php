<?php

namespace App\Services;

use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReferralService
{
    /**
     * Generate a unique referral code for a user for a specific event.
     */
    public function generateReferralLink(User $user, Event $event): string
    {
        // Simple hash based on user and event
        $code = substr(md5($user->id . '-' . $event->id . '-' . config('app.key')), 0, 8);
        
        return route('events.show', [
            'event' => $event->slug, 
            'ref' => $code
        ]);
    }

    /**
     * Record a successful referral purchase.
     */
    public function recordReferralSale(string $referralCode, float $amount)
    {
        // In a full implementation, you would look up which user owns the referral code,
        // and credit them with a percentage of the sale or reward points.
        
        // Pseudo-code for DB insert if we had a referrals table
        /*
        $referrerId = $this->getUserIdFromCode($referralCode);
        if ($referrerId) {
            DB::table('referrals')->insert([
                'referrer_id' => $referrerId,
                'amount_earned' => $amount * 0.05, // 5% commission
                'created_at' => now(),
            ]);
        }
        */
    }
}
