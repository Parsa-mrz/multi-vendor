<?php

namespace App\Mail;

use App\Events\UserLoggedIn;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserLoggedInNotification
 *
 * Mailable class for user login notification.
 * Sends a notification email to the user who logged in.
 *
 * @package App\Mail
 */
class UserLoggedInNotification extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * The user instance.
     *
     * @var UserLoggedIn
     */
    public $event;


    /**
     * Create a new message instance.
     */
    public function __construct(UserLoggedIn $event)
    {
        $this->event = $event;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Logged In Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-logged-in-notification',
            with: [
                'email' => $this->event->user->email,
                'ipAddress' => $this->event->ipAddress,
                'loginTime' => $this->event->loginTime,
            ],
        );
    }
}
