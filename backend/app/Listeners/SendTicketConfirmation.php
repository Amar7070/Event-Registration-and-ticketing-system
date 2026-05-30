<?php

namespace App\Listeners;

use App\Events\TicketBooked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTicketConfirmation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(\App\Events\TicketBooked $event): void
    {
        $event->ticket->user->notify(new \App\Notifications\TicketBookedNotification($event->ticket));
    }
}
