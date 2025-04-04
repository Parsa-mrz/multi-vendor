<?php

namespace App\Services\OtpSenders;

use App\Interfaces\OtpSenderInterface;
use App\Mail\EmailOtp;
use Illuminate\Support\Facades\Mail;

/**
 * Class EmailOtpSender
 *
 * Responsible for sending OTP via email.
 * Implements the OtpSenderInterface interface to define the contract for sending OTPs.
 */
class EmailOtpSender implements OtpSenderInterface
{
    /**
     * Send the OTP to a recipient via email.
     *
     * This method sends an OTP to the specified recipient using the EmailOtp Mailable class.
     *
     * @param  string  $recipient  The email address of the recipient.
     * @param  string  $otp  The OTP to be sent to the recipient.
     * @return void
     */
    public function send(string $recipient, string $otp): void
    {
        Mail::to($recipient)->send(new EmailOtp($otp,$recipient));
    }
}
