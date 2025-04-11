<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event MessageRead
 *
 * Triggered when a message has been marked as read.
 * Broadcasts the message read status over a private channel.
 */
class MessageRead implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * The message instance that was read.
     *
     * @var \App\Models\Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Message  $message  The message marked as read.
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel> The private channel for the conversation.
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
     * @return array<string, mixed> The data about the read message.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'read' => $this->message->read,
            'updated_at' => $this->message->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Customize the broadcast event name.
     *
     * @return string The name of the broadcasted event.
     */
    public function broadcastAs(): string
    {
        return 'message.read';
    }
}
