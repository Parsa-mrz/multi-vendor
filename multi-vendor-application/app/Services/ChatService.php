<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\User;

class ChatService
{
    public function canStartConversation(User $authUser, User $otherUser): bool
    {
        $roles = [$authUser->role, $otherUser->role];

        return
            in_array('admin', $roles) ||
            ($authUser->isCustomer () && $otherUser->isVendor ()) ||
            ($authUser->isVendor () && $otherUser->isCustomer ());
    }

    public function getOrCreateConversation(User $authUser, User $otherUser): Conversation
    {
        if (!$this->canStartConversation($authUser, $otherUser)) {
            abort(403, 'You cannot chat with this user.');
        }

        return Conversation::firstOrCreate(
            [
                ['user_one_id', '=', min($authUser->id, $otherUser->id)],
                ['user_two_id', '=', max($authUser->id, $otherUser->id)],
            ],
            [
                'user_one_id' => min($authUser->id, $otherUser->id),
                'user_two_id' => max($authUser->id, $otherUser->id),
            ]
        );
    }
}
