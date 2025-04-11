<?php

namespace App\Services;

use App\Events\ConversationStarted;
use App\Events\ConversationUpdated;
use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\Auth;

use function broadcast;

class ChatService
{
    /**
     * @var ConversationRepository
     */
    protected $conversationRepository;

    /**
     * @var MessageRepository
     */
    protected $messageRepository;

    /**
     * ChatService constructor.
     *
     * Inject the ConversationRepository and MessageRepository dependencies.
     */
    public function __construct(ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Get all conversations for the authenticated user.
     *
     * This method retrieves all conversations for the currently authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserConversations()
    {
        return $this->conversationRepository->getConversations(Auth::user());
    }

    /**
     * Start a new conversation or select an existing one.
     *
     * This method either creates a new conversation between the authenticated user
     * and the recipient, or returns an existing conversation if it already exists.
     * It also broadcasts an event when a new conversation is started.
     *
     * @param  int  $recipientId
     */
    public function startOrSelectConversation($recipientId): Conversation
    {
        $existingConversation = $this->conversationRepository->getConversationByUserId(Auth::id(), $recipientId);

        if (! $existingConversation) {
            $conversation = $this->conversationRepository->create([
                'user_id' => Auth::id(),
                'recipient_id' => $recipientId,
            ]);
            broadcast(new ConversationStarted($conversation));

            return $conversation;
        }

        return $existingConversation;
    }

    /**
     * Select an existing conversation by its ID.
     *
     * This method retrieves a conversation based on the provided conversation ID.
     *
     * @param  int  $conversationId
     */
    public function selectConversation($conversationId): Conversation
    {
        return $this->conversationRepository->find($conversationId);
    }

    /**
     * Get all messages for a specific conversation.
     *
     * This method retrieves all messages associated with a given conversation.
     */
    public function getMessages(Conversation $conversation): array
    {
        return $this->messageRepository->getMessages($conversation);
    }

    /**
     * Send a new message in a conversation.
     *
     * This method sends a new message in the specified conversation.
     * If the message body is empty, no message is sent.
     * It broadcasts an event when the message is sent and the conversation is updated.
     */
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

        broadcast(new MessageSent($message));
        broadcast(new ConversationUpdated($conversation));
    }

    /**
     * Mark all unread messages as read in a conversation.
     *
     * This method retrieves all unread messages in a conversation and marks them as read.
     * It broadcasts an event for each message that is marked as read.
     */
    public function markMessagesAsRead(Conversation $conversation): void
    {
        $unreadMessages = $this->messageRepository->getUnreadMessages($conversation, Auth::id());
        foreach ($unreadMessages as $message) {
            $message->read = true;
            $message->save();
            broadcast(new MessageRead($message));
        }
    }
}
