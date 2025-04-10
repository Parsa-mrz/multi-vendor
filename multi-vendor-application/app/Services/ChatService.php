<?php

namespace App\Services;

use App\Events\ConversationStarted;
use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\Auth;
use function broadcast;

class ChatService
{
    protected $conversationRepository;
    protected $messageRepository;

    public function __construct(ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
    }

    public function getUserConversations()
    {
        return $this->conversationRepository->getConversations(Auth::user());
    }

    public function startOrSelectConversation($recipientId): Conversation
    {
        $existingConversation = $this->conversationRepository->getConversationByUserId(Auth::id(), $recipientId);

        if (!$existingConversation) {
            $conversation = $this->conversationRepository->create([
                'user_id' => Auth::id(),
                'recipient_id' => $recipientId,
            ]);
            \Illuminate\Support\Facades\Log::info("Broadcasting ConversationStarted for user {$recipientId}");
            ConversationStarted::dispatch($conversation);
            return $conversation;
        }

        return $existingConversation;
    }

    public function selectConversation($conversationId): Conversation
    {
        return $this->conversationRepository->find($conversationId);
    }

    public function getMessages(Conversation $conversation): array
    {
        return $this->messageRepository->getMessages($conversation);
    }

    public function sendMessage(Conversation $conversation, string $messageBody): void
    {
        if (empty($messageBody)) {
            return;
        }

        $message = $this->messageRepository->create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $messageBody,
        ]);

        MessageSent::dispatch ($message);
    }

    public function markMessagesAsRead(Conversation $conversation): void
    {
        $unreadMessages = $this->messageRepository->getUnreadMessages($conversation, Auth::id());
        foreach ($unreadMessages as $message) {
            $message->read = true;
            $message->save();
            MessageRead::dispatch($message);
        }
    }

    public function getUnreadMessages(Conversation $conversation)
    {
        return $this->messageRepository->getUnreadMessages($conversation, Auth::id());
    }
}
