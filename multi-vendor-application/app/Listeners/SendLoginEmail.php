<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Mail\UserLoggedInNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLoginEmail
{
    /**
     * Handle the event.
     *
     * @param UserLoggedIn $event
     */
    public function handle(UserLoggedIn $event): void
    {
        try {
            Mail::to($event->user->email)->send(new UserLoggedInNotification($event));
        } catch (\Exception $e) {
            \Log::error('Error sending email: ' . $e->getMessage());
        }
    }
}
