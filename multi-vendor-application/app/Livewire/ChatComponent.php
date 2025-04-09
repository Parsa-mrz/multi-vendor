<?php
namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatComponent extends Component
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
            Log::warning('Send message aborted: No conversation selected or empty message');
            return;
        }

        try {
            $message = Message::create([
                'conversation_id' => $this->selectedConversation->id,
                'sender_id' => Auth::id(),
                'body' => $this->newMessage,
            ]);

            Log::info('Message created', ['message_id' => $message->id]);

            broadcast(new MessageSent($message))->toOthers();

            Log::info('Message broadcasted', ['message_id' => $message->id]);

            $this->messages[] = [
                'id' => $message->id,
                'body' => $message->body,
                'sender_id' => $message->sender_id,
                'created_at' => $message->created_at->toDateTimeString(),
            ];

            $this->newMessage = '';
        } catch (\Exception $e) {
            Log::error('Failed to send message', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'conversation_id' => $this->selectedConversation->id ?? null,
                'user_id' => Auth::id(),
                'message_body' => $this->newMessage,
            ]);
            throw $e; // Re-throw for further debugging if needed
        }
    }

    public function receiveMessage($message)
    {
        Log::debug('Message received in Livewire', $message);
        $this->messages[] = $message;
        $this->dispatch('message-received');
    }

    public function render()
    {
        return view('livewire.chat-component');
    }
}
