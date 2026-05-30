<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPromotion extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    public function plan()
    {
        return $this->belongsTo(EventPromotionPlan::class, 'plan_id');
    }
}
