<?php

namespace App\Repositories;

use App\Interfaces\MessageRepositoryInterface;
use App\Models\Conversation;
use App\Models\Message;

class MessageRepository implements MessageRepositoryInterface
{


    /**
     * @param  array  $data
     *
     * @return Message
     */
    public function create ( array $data ): Message
    {
        return Message::create($data);
    }

    /**
     * @param  Conversation  $conversation
     *
     * @return array
     */
    public function getMessages ( Conversation $conversation ): array
    {
        return $conversation->messages ()->latest ()->get ()->reverse ()->toArray();
    }
}
