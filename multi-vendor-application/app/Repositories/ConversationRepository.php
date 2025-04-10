<?php

namespace App\Repositories;

use App\Interfaces\ConversationRepositoryInterface;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use function array_merge;
use function dd;

class ConversationRepository implements ConversationRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param  array  $data
     *
     * @return Conversation
     */
    public function create ( array $data ): Conversation
    {
        return Conversation::create($data);
    }

    /**
     * @return Collection
     */
    public function getConversations (User $user): Collection
    {
        return $user->sentConversations->merge($user->receivedConversations)->unique('id');
    }

    public function getConversationByUserId (int $userId,int $recipientId){
        return Conversation::where(function ($query) use ($recipientId,$userId) {
            $query->where('user_id', $userId)->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($recipientId,$userId) {
            $query->where('user_id', $recipientId)->where('recipient_id', $userId);
        })->first();
    }

    /**
     * @param  int  $conversationId
     *
     * @return Conversation|null
     */
    public function find ( int $conversationId ): ?Conversation
    {
        return Conversation::find($conversationId);
    }
}
