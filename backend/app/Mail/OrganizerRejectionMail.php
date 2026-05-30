<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizerRejectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $organizer;
    public string $reason;

    public function __construct(User $organizer, string $reason)
    {
        $this->organizer = $organizer;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Organizer application status')
            ->view('emails.organizer-rejected')
            ->with([
                'organizer' => $this->organizer,
                'reason' => $this->reason,
            ]);
    }
}
