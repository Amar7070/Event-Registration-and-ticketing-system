<?php

namespace App\Models;

use Database\Factories\TicketTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected static function newFactory(): TicketTypeFactory
    {
        return TicketTypeFactory::new();
    }

    protected $guarded = ['id'];

    protected $casts = [
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'perks' => 'array',
        'is_active' => 'boolean',
        'is_transferable' => 'boolean',
        'is_refundable' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
