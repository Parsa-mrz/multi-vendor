<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserLoggedIn
 *
 * Event triggered when a user logs in.
 * Contains user details, IP address, and login time.
 */
class UserLoggedIn
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    public $user;

    /**
     * The IP address of the user.
     *
     * @var string|null
     */
    public $ipAddress;

    /**
     * The login timestamp.
     *
     * @var string
     */
    public $loginTime;

    /**
     * Create a new event instance.
     *
     * @param  User  $user  The authenticated user.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->ipAddress = request()->ip();
        $this->loginTime = now();
    }
}
