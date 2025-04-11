<?php

namespace App\Livewire\Chat;

use App\Helpers\SweetAlertHelper;
use App\Models\Conversation;
use App\Services\ChatService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Component;

use function route;

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
        'conversationUpdated' => 'handleConversationUpdated',
    ];

    public function boot(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function mount(?int $recipientId = null)
    {
        $this->recipientId = $recipientId;
        $this->conversations = $this->chatService->getUserConversations();

        if (Auth::id() === $this->recipientId) {
            SweetAlertHelper::error(
                $this,
                "You can't send a message to yourself",
                '',
                route('chat.index')
            );

            return;
        }

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
            $this->refreshConversations();
        }
    }

    public function markAsRead(): void
    {
        if ($this->selectedConversation) {
            $this->chatService->markMessagesAsRead($this->selectedConversation);
            $this->refreshConversations();
        }
    }

    public function handleConversationStarted(): void
    {
        $this->refreshConversations();
    }

    public function handleMessageReceived(array $event): void
    {
        if ($this->selectedConversation && $event['conversation_id'] === $this->selectedConversation->id) {
            $this->messages[] = $event;
            $this->dispatch('message-updated');
            $this->markAsRead();
        }
        $this->refreshConversations();
    }

    public function handleMessageRead(array $event): void
    {
        if ($this->selectedConversation && $event['conversation_id'] === $this->selectedConversation->id) {
            foreach ($this->messages as &$message) {
                if ($message['id'] === $event['id']) {
                    $message['read'] = $event['read'];
                    $message['updated_at'] = $event['updated_at'];
                    break;
                }
            }
            foreach ($this->conversations as $conversation) {
                if ($conversation->id === $event['conversation_id']) {
                    $lastMessage = $conversation->messages->last();
                    if ($lastMessage && $lastMessage->id === $event['id']) {
                        $lastMessage->read = $event['read'];
                        $lastMessage->updated_at = $event['updated_at'];
                    }
                    break;
                }
            }
            $this->dispatch('message-updated');
        }
        $this->refreshConversations();
    }

    public function handleConversationUpdated(array $event): void
    {
        $this->refreshConversations();
    }

    public function refreshConversations(): void
    {
        $this->conversations = $this->chatService->getUserConversations();
    }

    #[Title('Chat')]
    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
