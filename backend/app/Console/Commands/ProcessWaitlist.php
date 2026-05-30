<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\TicketType;
use App\Services\WaitlistService;

class ProcessWaitlist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waitlist:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the event waitlists and notify users if capacity becomes available';

    /**
     * Execute the console command.
     */
    public function handle(WaitlistService $waitlistService)
    {
        $this->info('Processing waitlists...');

        // Find events that are upcoming
        $events = Event::where('start_date', '>', now())->get();

        $processedCount = 0;

        foreach ($events as $event) {
            foreach ($event->ticketTypes as $ticketType) {
                if ($ticketType->quantity_sold < $ticketType->quantity_total) {
                    // Capacity available!
                    // Get people from waitlist
                    $entries = $event->waitlists()
                        ->where('ticket_type_id', $ticketType->id)
                        ->where('status', 'waiting')
                        ->orderBy('position', 'asc')
                        ->get();

                    foreach ($entries as $entry) {
                        // Notify user and mark entry as notified
                        $entry->update([
                            'status' => 'notified',
                            'notified_at' => now(),
                            'expires_at' => now()->addHours(24) // 24 hours to claim
                        ]);
                        
                        $entry->user->notify(new \App\Notifications\WaitlistAvailableNotification($entry));
                        
                        $processedCount++;
                    }
                }
            }
        }

        $this->info("Processed waitlists. Notified {$processedCount} users.");
    }
}
