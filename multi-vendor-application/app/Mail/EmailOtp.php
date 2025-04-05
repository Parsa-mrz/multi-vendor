<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Class EmailOtp
 *
 * This Mailable class is responsible for sending the email containing the One-Time Password (OTP)
 * for email verification. It sends the OTP to the recipient's email address.
 */
class EmailOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The OTP to be sent to the recipient.
     *
     * @var string
     */
    public $otp;

    /**
     * The recipient's email address.
     *
     * @var string
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param  string  $otp  The one-time password to be sent in the email.
     * @param  string  $email  The recipient's email address.
     */
    public function __construct(string $otp, string $email)
    {
        $this->otp = $otp;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     *
     * The envelope contains the metadata of the message, such as the subject.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification',
        );
    }

    /**
     * Get the message content definition.
     *
     * This method defines the view for the email content and the data to pass to it.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.email-otp',
            with: [
                'otp' => $this->otp,
                'email' => $this->email,
            ]
        );
    }
}
