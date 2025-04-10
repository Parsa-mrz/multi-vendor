<?php

namespace App\Interfaces;

use App\Models\Conversation;
use App\Models\Message;

interface MessageRepositoryInterface
{
    public function create(array $data):Message;
    public function getMessages(Conversation $conversation):array;
}
