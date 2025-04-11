<?php

namespace App\Repositories;

use App\Interfaces\ConversationRepositoryInterface;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ConversationRepository implements ConversationRepositoryInterface
{
    /**
     * Create a new conversation.
     *
     * This method creates a new conversation in the database with the given data.
     */
    public function create(array $data): Conversation
    {
        return Conversation::create($data);
    }

    /**
     * Get all conversations for a user.
     *
     * This method retrieves all conversations for a user, combining both sent and received conversations,
     * and returns them as a unique collection of conversations based on the conversation ID.
     */
    public function getConversations(User $user): Collection
    {
        return $user->sentConversations->merge($user->receivedConversations)->unique('id');
    }

    /**
     * Get a conversation between two users by their IDs.
     *
     * This method checks if a conversation exists between the specified user and recipient.
     * It will return the first conversation it finds where the user and recipient match in either order.
     */
    public function getConversationByUserId(int $userId, int $recipientId): ?Conversation
    {
        return Conversation::where(function ($query) use ($recipientId, $userId) {
            $query->where('user_id', $userId)->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($recipientId, $userId) {
            $query->where('user_id', $recipientId)->where('recipient_id', $userId);
        })->first();
    }

    /**
     * Find a conversation by its ID.
     *
     * This method retrieves a conversation based on the given conversation ID.
     * If the conversation is not found, it returns null.
     */
    public function find(int $conversationId): ?Conversation
    {
        return Conversation::find($conversationId);
    }
}
