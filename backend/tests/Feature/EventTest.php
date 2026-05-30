<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;

class EventTest extends TestCase
{

    public function test_public_can_view_event_listing()
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_only_approved_organizers_can_create_events()
    {
        $organizer = User::factory()->create(['is_approved' => true]);
        $organizer->assignRole('organizer');

        $category = \App\Models\EventCategory::factory()->create();

        $response = $this->actingAs($organizer)->post('/organizer/events', [
            'title' => 'Test Event',
            'category_id' => $category->id,
            'type' => 'physical',
            'visibility' => 'public',
            'short_description' => 'Test short description',
            'description' => 'Test long description',
            'start_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(11)->format('Y-m-d H:i:s'),
            'timezone' => 'UTC',
            'total_capacity' => 100,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('events', [
            'title' => 'Test Event'
        ]);
    }
}
