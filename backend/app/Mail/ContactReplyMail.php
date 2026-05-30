<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $replyMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, string $replyMessage)
    {
        $this->contact = $contact;
        $this->replyMessage = $replyMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . ($this->contact->subject ?: 'Your message to SmartEvent'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
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

    private function buildHtml(): string
    {
        return '
        <div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; color: #333;">
            <h2>Hello ' . htmlspecialchars($this->contact->name) . ',</h2>
            <p>Thank you for reaching out to us. An administrator has replied to your message:</p>
            <div style="background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 20px; white-space: pre-wrap;">
                ' . htmlspecialchars($this->replyMessage) . '
            </div>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #666; font-size: 14px;"><strong>Your Original Message:</strong></p>
            <blockquote style="color: #666; font-size: 14px; font-style: italic; border-left: 4px solid #ddd; padding-left: 10px; white-space: pre-wrap;">
                ' . htmlspecialchars($this->contact->message) . '
            </blockquote>
            <br>
            <p>Best regards,<br><strong>SmartEvent Ecosystem Team</strong></p>
        </div>';
    }
}
