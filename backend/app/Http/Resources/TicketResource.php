<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_reference' => $this->booking_reference,
            'event' => [
                'title' => $this->event->title,
                'date' => $this->event->start_date->toIso8601String(),
            ],
            'type' => $this->ticketType->name,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'qr_code_url' => $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null,
            'purchased_at' => $this->created_at->toIso8601String(),
        ];
    }
}
