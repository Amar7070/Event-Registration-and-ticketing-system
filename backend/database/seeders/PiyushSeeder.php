<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Venue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PiyushSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Organizer
        $organizer = User::updateOrCreate(
            ['email' => 'piyush@smartevent.com'],
            [
                'name' => 'Piyush Kumar',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_approved' => true,
            ]
        );
        $organizer->assignRole('organizer');

        $category = EventCategory::first();
        $venue = Venue::first();

        // 2. Create 3 Events
        for ($i = 1; $i <= 3; $i++) {
            Event::updateOrCreate(
                ['slug' => 'piyush-event-' . $i],
                [
                    'organizer_id' => $organizer->id,
                    'category_id' => $category ? $category->id : 1,
                    'venue_id' => $venue ? $venue->id : 1,
                    'title' => 'Piyush Tech Summit ' . $i,
                    'short_description' => 'A premier tech gathering hosted by Piyush.',
                    'description' => 'This is a test event created by PiyushSeeder.',
                    'banner_image' => 'https://images.unsplash.com/photo-1540575861501-7ad05823c23d?auto=format&fit=crop&q=80&w=1000',
                    'start_date' => now()->addDays($i * 5),
                    'end_date' => now()->addDays($i * 5 + 1),
                    'is_restricted' => false,
                    'is_featured' => true,
                    'status' => 'published',
                    'type' => 'physical',
                    'total_capacity' => 100,
                    'registered_count' => 0,
                    'views_count' => 0
                ]
            );
        }
    }
}


