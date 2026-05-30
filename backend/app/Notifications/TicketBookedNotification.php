<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketBookedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
        return (new MailMessage)
                    ->subject('Ticket Booking Pending - ' . $this->ticket->event->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your booking request for ' . $this->ticket->event->title . ' has been received.')
                    ->line('Booking Reference: ' . $this->ticket->booking_reference)
                    ->line('Please complete your payment to confirm your tickets.')
                    ->action('Complete Payment', url('/tickets/' . $this->ticket->id))
                    ->line('Thank you for using SmartEvent!');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        return "Hi {$notifiable->name}, your booking for {$this->ticket->event->title} is pending. Ref: {$this->ticket->booking_reference}. Pay now at SmartEvent.";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'event_title' => $this->ticket->event->title,
            'booking_reference' => $this->ticket->booking_reference,
            'message' => 'Your booking for ' . $this->ticket->event->title . ' is pending payment.',
        ];
    }
}
