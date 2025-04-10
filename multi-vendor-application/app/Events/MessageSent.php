<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info("Broadcasting message to conversation.{$this->message->conversation_id}");
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id)
        ];

    }

    public function broadcastWith()
    {
        return [
                'id' => $this->message->id,
                'body' => $this->message->body,
                'sender_id' => $this->message->sender_id,
                'read' => $this->message->read,
                'conversation_id' => $this->message->conversation_id,
                'created_at' => $this->message->created_at,
                'updated_at' => $this->message->updated_at
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
