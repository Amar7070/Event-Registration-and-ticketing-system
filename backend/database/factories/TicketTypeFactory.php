<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketTypeFactory extends Factory
{
    protected $model = TicketType::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => 'General Admission',
            'description' => $this->faker->sentence(6),
            'price' => 100,
            'quantity_total' => 100,
            'quantity_sold' => 0,
            'sale_starts_at' => now()->subDay(),
            'sale_ends_at' => now()->addWeeks(2),
            'perks' => [],
            'is_active' => true,
            'is_transferable' => false,
            'is_refundable' => true,
        ];
    }
}
