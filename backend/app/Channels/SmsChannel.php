<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class SmsChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);
        $to = $notifiable->phone;

        if (!$to) {
            return;
        }

        // Twilio Logic (Placeholder - needs env credentials)
        /*
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        if ($sid && $token && $from) {
            $client = new Client($sid, $token);
            $client->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);
        }
        */
        
        \Log::info("SMS sent to {$to}: {$message}");
    }
}
