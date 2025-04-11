<?php

namespace App\Interfaces;

use App\Models\Conversation;
use App\Models\Message;

/**
 * Interface MessageRepositoryInterface
 *
 * Defines the contract for managing messages within conversations.
 * The interface includes methods for creating messages, retrieving
 * messages from a conversation, and getting unread messages for a user.
 */
interface MessageRepositoryInterface
{
    /**
     * Create a new message in the database.
     *
     * @param  array  $data  The data required to create a new message (e.g., sender, conversation_id, body, etc.).
     * @return \App\Models\Message The created message instance.
     */
    public function create(array $data): Message;

    /**
     * Get all messages for a specific conversation.
     *
     * @param  \App\Models\Conversation  $conversation  The conversation for which to retrieve messages.
     * @return array An array of messages associated with the given conversation.
     */
    public function getMessages(Conversation $conversation): array;

    /**
     * Get all unread messages for a specific user in a conversation.
     *
     * @param  \App\Models\Conversation  $conversation  The conversation to check for unread messages.
     * @param  int  $userId  The ID of the user to check unread messages for.
     * @return array An array of unread messages for the specified user in the given conversation.
     */
    public function getUnreadMessages(Conversation $conversation, int $userId): array;

    public function getAllUnreadMessages(int $userId): array;
}
