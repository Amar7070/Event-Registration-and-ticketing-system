<?php

namespace Database\Factories;

use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventCategoryFactory extends Factory
{
    protected $model = EventCategory::class;

    public function definition(): array
    {
        $name = $this->faker->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'icon' => null,
            'color' => '#4F46E5',
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
