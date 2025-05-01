<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// usable when one wants to queue the email
class PasswordResetMail extends Mailable implements ShouldQueue
// class PasswordResetMail extends Mailable 
{
    use Queueable, SerializesModels;
    public $tries = 3;    // Max attempts before failing
    public $backoff = [30, 60, 120];    // Delay (seconds) between retries

    public $user;
    public $resetUrl;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $resetUrl,$token)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
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

    public function build()
    {
        $url = url("/reset-password-form?token={$this->token}");
        return $this->markdown('emails.password-reset')
            ->with(['url' => $url]);
    }
}
