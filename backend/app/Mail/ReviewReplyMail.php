<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewReplyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;
    public $attendeeName;
    public $replyMessage;
    public $customNote;
    public $organizerName;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, string $attendeeName, string $replyMessage, ?string $customNote)
    {
        $this->event = $event;
        $this->attendeeName = $attendeeName;
        $this->replyMessage = $replyMessage;
        $this->customNote = $customNote;
        $this->organizerName = $event->organizer->name ?? 'The Event Team';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Re: Your Review for {$this->event->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.review-reply',
            with: [
                'eventTitle' => $this->event->title,
                'organizerName' => $this->organizerName,
                'attendeeName' => $this->attendeeName,
                'replyMessage' => $this->replyMessage,
                'customNote' => $this->customNote,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
