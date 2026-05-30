<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizerApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $organizer;

    public function __construct(User $organizer)
    {
        $this->organizer = $organizer;
    }

    public function build()
    {
        return $this->subject('Your organizer account has been approved')
            ->view('emails.organizer-approved')
            ->with(['organizer' => $this->organizer]);
    }
}
