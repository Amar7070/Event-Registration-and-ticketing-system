<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        $startDate = $this->faker->dateTimeBetween('+1 week', '+2 weeks');
        $endDate = (clone $startDate)->modify('+2 hours');

        return [
            'organizer_id' => User::factory(),
            'category_id' => EventCategory::factory(),
            'venue_id' => null,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'short_description' => $this->faker->paragraph(2),
            'description' => $this->faker->paragraphs(3, true),
            'banner_image' => null,
            'thumbnail_image' => null,
            'type' => 'physical',
            'online_link' => null,
            'status' => 'published',
            'visibility' => 'public',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'registration_start' => now(),
            'registration_end' => now()->addWeek(),
            'total_capacity' => 100,
            'registered_count' => 0,
            'is_featured' => false,
            'is_recurring' => false,
            'recurrence_pattern' => null,
            'timezone' => 'UTC',
            'language' => 'en',
            'requires_approval' => false,
            'tags' => [],
            'faqs' => [],
            'views_count' => 0,
        ];
    }
}
