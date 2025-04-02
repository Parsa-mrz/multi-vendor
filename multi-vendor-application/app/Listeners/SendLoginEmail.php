<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Mail\UserLoggedInNotification;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendLoginEmail
 *
 * Listener for the UserLoggedIn event.
 * Sends a login notification email to the user.
 */
class SendLoginEmail
{
    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event  The event instance.
     */
    public function handle(UserLoggedIn $event): void
    {
        try {
            Mail::to($event->user->email)->send(new UserLoggedInNotification($event));
        } catch (\Exception $e) {
            \Log::error('Error sending email: '.$e->getMessage());
        }
    }
}
