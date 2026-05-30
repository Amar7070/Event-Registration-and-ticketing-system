<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'notified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeNotified($query)
    {
        return $query->where('status', 'notified');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }
}
