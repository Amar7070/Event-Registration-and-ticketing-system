<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Support\Str;

class DemoBookingSeeder extends Seeder
{
    public function run(): void
    {
        $attendee = User::where('email', 'attendee@smartevent.com')->first();
        if (!$attendee) return;

        $events = Event::with('ticketTypes')->limit(3)->get();
        if ($events->count() < 3) return;

        // Event 1: Current month (in 3 days)
        $event1 = $events[0];
        $event1->update([
            'start_date' => now()->addDays(3)->setHour(18)->setMinute(0),
            'end_date' => now()->addDays(3)->setHour(22)->setMinute(0),
        ]);

        // Event 2: Next month (in 35 days)
        $event2 = $events[1];
        $event2->update([
            'start_date' => now()->addDays(35)->setHour(14)->setMinute(0),
            'end_date' => now()->addDays(35)->setHour(18)->setMinute(0),
        ]);

        // Event 3: Past month (15 days ago)
        $event3 = $events[2];
        $event3->update([
            'start_date' => now()->subDays(15)->setHour(9)->setMinute(0),
            'end_date' => now()->subDays(15)->setHour(17)->setMinute(0),
        ]);

        // Create booking 1 (confirmed, upcoming)
        $type1 = $event1->ticketTypes->first();
        if ($type1) {
            Ticket::updateOrCreate(
                ['booking_reference' => 'SE-2026-X8Y7Z2'],
                [
                    'event_id' => $event1->id,
                    'ticket_type_id' => $type1->id,
                    'user_id' => $attendee->id,
                    'quantity' => 2,
                    'unit_price' => $type1->price,
                    'total_amount' => $type1->price * 2,
                    'status' => 'confirmed',
                    'qr_token' => Str::random(32),
                ]
            );
        }

        // Create booking 2 (confirmed, upcoming)
        $type2 = $event2->ticketTypes->first();
        if ($type2) {
            Ticket::updateOrCreate(
                ['booking_reference' => 'SE-2026-A1B2C3'],
                [
                    'event_id' => $event2->id,
                    'ticket_type_id' => $type2->id,
                    'user_id' => $attendee->id,
                    'quantity' => 1,
                    'unit_price' => $type2->price,
                    'total_amount' => $type2->price,
                    'status' => 'confirmed',
                    'qr_token' => Str::random(32),
                ]
            );
        }

        // Create booking 3 (used / past)
        $type3 = $event3->ticketTypes->first();
        if ($type3) {
            Ticket::updateOrCreate(
                ['booking_reference' => 'SE-2026-M4N5O6'],
                [
                    'event_id' => $event3->id,
                    'ticket_type_id' => $type3->id,
                    'user_id' => $attendee->id,
                    'quantity' => 1,
                    'unit_price' => $type3->price,
                    'total_amount' => $type3->price,
                    'status' => 'confirmed',
                    'qr_token' => Str::random(32),
                ]
            );
        }
    }
}
