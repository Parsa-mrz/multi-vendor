<?php

namespace App\Livewire\Chat;

use App\Services\ChatService;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class ChatBox extends Component
{
    public Collection $conversations;
    public ?Conversation $selectedConversation = null;
    public array $messages = [];
    public string $newMessage = '';
    public ?int $recipientId = null;
    protected ChatService $chatService;

    protected $listeners = [
        'conversationStarted' => 'handleConversationStarted',
        'messageReceived' => 'handleMessageReceived',
        'messageRead' => 'handleMessageRead',
    ];

    public function boot(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function mount(?int $recipientId = null): void
    {
        $this->recipientId = $recipientId;
        $this->conversations = $this->chatService->getUserConversations();

        if ($this->recipientId) {
            $this->selectedConversation = $this->chatService->startOrSelectConversation($this->recipientId);
            if ($this->selectedConversation) {
                Session::put('selected_conversation_id', $this->selectedConversation->id);
                $this->loadMessages();
                $this->markAsRead();
                $this->refreshConversations();
                $this->dispatch('conversation-selected', conversationId: $this->selectedConversation->id);
                $this->redirectRoute('chat.index');
            }
        } elseif (Session::has('selected_conversation_id')) {
            $this->selectedConversation = $this->chatService->selectConversation(Session::pull('selected_conversation_id'));
            if ($this->selectedConversation) {
                $this->loadMessages();
                $this->markAsRead();
                $this->dispatch('conversation-selected', conversationId: $this->selectedConversation->id);
            }
        }
    }

    public function selectConversation(int $conversationId): void
    {
        $this->selectedConversation = $this->chatService->selectConversation($conversationId);
        $this->loadMessages();
        $this->markAsRead();
        $this->dispatch('conversation-selected', conversationId: $conversationId);
    }

    public function loadMessages(): void
    {
        if ($this->selectedConversation) {
            $this->messages = $this->chatService->getMessages($this->selectedConversation);
        }
    }

    public function sendMessage(): void
    {
        if ($this->selectedConversation && trim($this->newMessage)) {
            $this->chatService->sendMessage($this->selectedConversation, $this->newMessage);
            $this->newMessage = '';
        }
    }

    public function markAsRead(): void
    {
        if ($this->selectedConversation) {
            $this->chatService->markMessagesAsRead($this->selectedConversation);
        }
    }

    public function handleConversationStarted(): void
    {
        $this->refreshConversations();
        $this->dispatch('conversation-added');
    }

    public function handleMessageReceived(array $event): void
    {
        if ($this->selectedConversation && $event['conversation_id'] === $this->selectedConversation->id) {
            $this->messages[] = $event;
            $this->dispatch('message-updated');
            $this->markAsRead();
        }
    }

    public function handleMessageRead(array $event): void
    {
        if ($this->selectedConversation && $event['conversation_id'] === $this->selectedConversation->id) {
            foreach ( $this->messages as &$message ) {
                if ( $message[ 'id' ] === $event[ 'id' ] ) {
                    $message[ 'read' ]       = $event[ 'read' ];
                    $message[ 'updated_at' ] = $event[ 'updated_at' ];
                    break;
                }
            }
            foreach ( $this->conversations as $conversation ) {
                if ( $conversation->id === $event[ 'conversation_id' ] ) {
                    $lastMessage = $conversation->messages->last ();
                    if ( $lastMessage && $lastMessage->id === $event[ 'id' ] ) {
                        $lastMessage->read       = $event[ 'read' ];
                        $lastMessage->updated_at = $event[ 'updated_at' ];
                    }
                    break;
                }
            }
            $this->dispatch ( 'message-updated' );
        }
    }

    public function refreshConversations(): void
    {
        $this->conversations = $this->chatService->getUserConversations();
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
