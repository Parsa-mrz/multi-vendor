<?php

namespace App\Interfaces;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ConversationRepositoryInterface
 *
 * Defines the contract for managing conversations in the application.
 * The interface includes methods for creating conversations, finding
 * a conversation by ID, retrieving all conversations for a user,
 * and getting a conversation between two specific users.
 */
interface ConversationRepositoryInterface
{
    /**
     * Create a new conversation.
     *
     * @param  array  $data  The data needed to create a new conversation.
     * @return \App\Models\Conversation The created conversation instance.
     */
    public function create(array $data): Conversation;

    /**
     * Find a conversation by its ID.
     *
     * @param  int  $conversationId  The ID of the conversation to find.
     * @return \App\Models\Conversation|null The conversation if found, or null if not found.
     */
    public function find(int $conversationId): ?Conversation;

    /**
     * Get all conversations for a specific user.
     *
     * @param  \App\Models\User  $user  The user for whom the conversations are being retrieved.
     * @return \Illuminate\Database\Eloquent\Collection A collection of conversations associated with the user.
     */
    public function getConversations(User $user): Collection;

    /**
     * Get a conversation between two specific users.
     *
     * @param  int  $userId  The ID of the first user.
     * @param  int  $recipientId  The ID of the second user (the recipient).
     * @return \App\Models\Conversation|null The conversation if found, or null if not found.
     */
    public function getConversationByUserId(int $userId, int $recipientId): ?Conversation;
}
