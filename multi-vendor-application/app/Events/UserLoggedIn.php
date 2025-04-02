<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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

    public $ipAddress;
    public $loginTime;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->ipAddress = request()->ip();
        $this->loginTime = now();
    }
}
