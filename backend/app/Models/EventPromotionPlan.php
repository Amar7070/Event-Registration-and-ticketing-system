<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPromotionPlan extends Model
{
    protected $guarded = ['id'];

    public function promotions()
    {
        return $this->hasMany(EventPromotion::class, 'plan_id');
    }
}
