<?php

namespace App\Livewire\Chat;

use App\Services\ChatService;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use function dd;

class ChatBox extends Component
{
    public Collection $conversations;
    public ?Conversation $selectedConversation = null;
    public array $messages = [];
    public string $newMessage = '';
    public ?int $recipientId = null;

    protected ChatService $chatService;

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
                $this->refreshConversations();
                $this->dispatch('conversation-selected', conversationId: $this->selectedConversation->id);
                $this->redirectRoute('chat.index');
            }
        } elseif (Session::has('selected_conversation_id')) {
            $this->selectedConversation = $this->chatService->selectConversation(Session::pull('selected_conversation_id'));
            if ($this->selectedConversation) {
                $this->loadMessages();
                $this->dispatch('conversation-selected', conversationId: $this->selectedConversation->id);
            }
        }
    }

    public function selectConversation(int $conversationId): void
    {
        $this->selectedConversation = $this->chatService->selectConversation($conversationId);
        $this->loadMessages();
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

    #[On('conversation-started')]
    public function handleConversationStarted(array $event): void
    {
        $this->refreshConversations();
        $this->dispatch('conversation-added');
    }

    #[On('message-received')]
    public function handleMessageReceived(array $event): void
    {
        Log::info('Message received event:', $event);
        if ($this->selectedConversation && $event['conversation_id'] === $this->selectedConversation->id) {
            $this->messages[] = $event;
            $this->dispatch('message-updated');
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
