<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payment;

class PaymentCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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
        $invoiceUrl = url('/invoices/' . $this->payment->invoice->id . '/download');

        return (new MailMessage)
                    ->subject('Payment Confirmed - ' . $this->payment->event->title)
                    ->greeting('Success!')
                    ->line('Your payment of ' . $this->payment->currency . ' ' . $this->payment->amount . ' has been confirmed.')
                    ->line('Your tickets for ' . $this->payment->event->title . ' are now confirmed.')
                    ->action('Download Invoice', $invoiceUrl)
                    ->line('You can also download your tickets from your profile.')
                    ->line('See you at the event!');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        return "Success! Your payment for {$this->payment->event->title} has been confirmed. Tickets are ready at SmartEvent.";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'event_title' => $this->payment->event->title,
            'amount' => $this->payment->amount,
            'message' => 'Payment confirmed for ' . $this->payment->event->title . '.',
        ];
    }
}
