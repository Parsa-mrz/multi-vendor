<?php

namespace App\Interfaces;

/**
 * Interface OtpSenderInterface
 *
 * Defines the contract for OTP (One-Time Password) senders.
 * This interface specifies the required method for sending OTPs to a recipient.
 */
interface OtpSenderInterface
{
    /**
     * Send the OTP to the recipient.
     *
     * This method is responsible for sending a one-time password (OTP) to a specified recipient.
     *
     * @param  string  $recipient  The recipient's identifier (e.g., email address or phone number).
     * @param  string  $otp  The one-time password (OTP) to be sent.
     */
    public function send(string $recipient, string $otp): void;
}
