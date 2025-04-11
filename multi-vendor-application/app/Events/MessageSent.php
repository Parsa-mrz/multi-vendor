<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event MessageSent
 *
 * Triggered when a new message is sent in a conversation.
 * This event is broadcast over a private channel for real-time updates.
 */
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * The message instance.
     *
     * @var \App\Models\Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Message  $message  The message that was sent.
     * @return void
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
        return [
            new PrivateChannel('conversation.'.$this->message->conversation_id),
        ];

    }

    /**
     * Define the data to broadcast with the event.
     *
     * @return array<string, mixed> The message data to be broadcasted.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'sender_id' => $this->message->sender_id,
            'read' => $this->message->read,
            'conversation_id' => $this->message->conversation_id,
            'created_at' => $this->message->created_at,
            'updated_at' => $this->message->updated_at,
        ];
    }

    /**
     * Customize the broadcast event name.
     *
     * @return string The name of the event.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
