<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use function broadcast;

class ChatBox extends Component
{
    public $conversations = [];
    public $selectedConversation = null;
    public $messages = [];
    public $newMessage = '';
    public $recipientId = null;

    public function mount($recipientId = null)
    {
        $this->recipientId = $recipientId;
        $user = Auth::user();
        $this->conversations = $user->sentConversations->merge($user->receivedConversations)->unique('id');

        if ($this->recipientId) {
            $this->startOrSelectConversation($this->recipientId);
        }
    }

    public function startOrSelectConversation($recipientId)
    {
        $existingConversation = Conversation::where(function ($query) use ($recipientId) {
            $query->where('user_id', Auth::id())->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($recipientId) {
            $query->where('user_id', $recipientId)->where('recipient_id', Auth::id());
        })->first();

        if (!$existingConversation) {
            $this->selectedConversation = Conversation::create([
                'user_id' => Auth::id(),
                'recipient_id' => $recipientId,
            ]);
        } else {
            $this->selectedConversation = $existingConversation;
        }

        $this->loadMessages();
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversation = Conversation::findOrFail($conversationId);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->selectedConversation->messages()->latest()->get()->reverse()->toArray();
    }

    public function sendMessage()
    {
        if (!$this->selectedConversation || empty($this->newMessage)) {
            return;
        }

            $message = Message::create([
                'conversation_id' => $this->selectedConversation->id,
                'sender_id' => Auth::id(),
                'body' => $this->newMessage,
            ]);

            broadcast(new MessageSent($message))->toOthers();

            $this->messages[] = [
                'id' => $message->id,
                'body' => $message->body,
                'sender_id' => $message->sender_id,
                'created_at' => $message->created_at->toDateTimeString(),
            ];

            $this->newMessage = '';


    }

    #[On('echo:conversation.1,MessageSent')]
    public function receiveMessage($message)
    {
        $this->messages[] = $message;
        $this->dispatch('message-received');
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
