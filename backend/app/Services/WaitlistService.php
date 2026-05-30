<?php

namespace App\Services;

use App\Models\Waitlist;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WaitlistService
{
    /**
     * Join the waitlist for an event and ticket type.
     */
    public function join(User $user, Event $event, TicketType $ticketType): Waitlist
    {
        return DB::transaction(function () use ($user, $event, $ticketType) {
            // Check if already on waitlist
            $existing = Waitlist::where('event_id', $event->id)
                ->where('ticket_type_id', $ticketType->id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['waiting', 'notified'])
                ->first();

            if ($existing) {
                throw new \Exception('You are already on the waitlist for this ticket type.');
            }

            // Get current max position
            $maxPosition = Waitlist::where('event_id', $event->id)
                ->where('ticket_type_id', $ticketType->id)
                ->max('position') ?? 0;

            return Waitlist::create([
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'user_id' => $user->id,
                'position' => $maxPosition + 1,
                'status' => 'waiting',
            ]);
        });
    }

    /**
     * Notify the next person on the waitlist when a slot becomes available.
     */
    public function notifyNext(Event $event, TicketType $ticketType)
    {
        $nextInLine = Waitlist::where('event_id', $event->id)
            ->where('ticket_type_id', $ticketType->id)
            ->waiting()
            ->orderBy('position', 'asc')
            ->first();

        if ($nextInLine) {
            $nextInLine->update([
                'status' => 'notified',
                'notified_at' => now(),
                'expires_at' => now()->addHours(24), // 24-hour window
            ]);

            // Fire notification
            $nextInLine->user->notify(new \App\Notifications\WaitlistAvailableNotification($nextInLine));
            
            return $nextInLine;
        }

        return null;
    }

    /**
     * Expire waitlist entries that haven't booked within the window.
     */
    public function processExpiredEntries()
    {
        $expired = Waitlist::notified()
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $entry) {
            $entry->update(['status' => 'expired']);
            
            // Notify the next person
            $this->notifyNext($entry->event, $entry->ticketType);
        }

        return $expired->count();
    }
}
