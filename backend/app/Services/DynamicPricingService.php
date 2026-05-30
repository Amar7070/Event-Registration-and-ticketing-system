<?php

namespace App\Services;

use App\Models\TicketType;

class DynamicPricingService
{
    /**
     * Calculate current dynamic price for a ticket type based on demand and time.
     */
    public function getCurrentPrice(TicketType $ticketType): float
    {
        return (float) $ticketType->price;
    }
}
