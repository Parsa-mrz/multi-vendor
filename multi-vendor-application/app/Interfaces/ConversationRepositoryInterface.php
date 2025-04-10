<?php

namespace App\Interfaces;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ConversationRepositoryInterface
{
    public function create(array $data):Conversation;

    public function find(int $conversationId): ?Conversation;

    public function getConversations(User $user):Collection;

    public function getConversationByUserId(int $userId,int $recipientId);
}
