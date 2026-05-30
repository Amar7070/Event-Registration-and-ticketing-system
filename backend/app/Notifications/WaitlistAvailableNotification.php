<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Waitlist;

class WaitlistAvailableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $waitlist;

    /**
     * Create a new notification instance.
     */
    public function __construct(Waitlist $waitlist)
    {
        $this->waitlist = $waitlist;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', \App\Channels\SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $bookingUrl = url('/events/' . $this->waitlist->event->slug . '?ticket_type=' . $this->waitlist->ticket_type_id);

        return (new MailMessage)
                    ->subject('Great News! A spot opened up for ' . $this->waitlist->event->title)
                    ->greeting('Hi ' . $notifiable->name . '!')
                    ->line('A ticket is now available for ' . $this->waitlist->event->title . '.')
                    ->line('You have a 24-hour window to complete your booking before the spot is offered to the next person.')
                    ->action('Book Now', $bookingUrl)
                    ->line('Hurry, don\'t miss out!')
                    ->line('Expires at: ' . $this->waitlist->expires_at->format('M d, Y H:i'));
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        return "Hi {$notifiable->name}, a spot for {$this->waitlist->event->title} is available! Book within 24h at SmartEvent.";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'waitlist_id' => $this->waitlist->id,
            'event_title' => $this->waitlist->event->title,
            'expires_at' => $this->waitlist->expires_at,
            'message' => 'A spot is available for ' . $this->waitlist->event->title . '. Book within 24 hours!',
        ];
    }
}
