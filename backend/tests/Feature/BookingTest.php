<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\TicketType;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_initiate_booking()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published', 'visibility' => 'public']);
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'price' => 100,
            'quantity_total' => 50,
            'quantity_sold' => 0
        ]);

        $response = $this->actingAs($user)->post(route('events.book.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Ticket booked successfully. Please complete the payment.');
        $response->assertRedirectContains('/payments/checkout');
    }
}
