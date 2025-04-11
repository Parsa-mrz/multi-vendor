<?php

namespace App\Repositories;

use App\Interfaces\MessageRepositoryInterface;
use App\Models\Conversation;
use App\Models\Message;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * Create a new message.
     *
     * This method creates a new message in the database with the given data.
     */
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    /**
     * Get all messages for a specific conversation.
     *
     * This method retrieves all messages in the given conversation,
     * orders them by the latest first, then reverses the collection to return
     * them in the order they were sent (oldest to newest).
     */
    public function getMessages(Conversation $conversation): array
    {
        return $conversation->messages()->latest()->get()->reverse()->toArray();
    }

    /**
     * Get unread messages for a specific user in a conversation.
     *
     * This method retrieves all unread messages for a user in the given conversation,
     * excluding messages that were sent by the user themselves.
     */
    public function getUnreadMessages(Conversation $conversation, int $userId): array
    {
        return Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->where('read', false)
            ->get()
            ->all();
    }

    /**
     * Get all unread messages for a specific user.
     *
     * This method retrieves all unread messages for a user,
     * excluding messages that were sent by the user themselves.
     */
    public function getAllUnreadMessages ( int $userId ): array
    {
        return Message::where('sender_id', '!=', $userId)
                      ->where('read', false)
                      ->get()
                      ->all();
    }}
