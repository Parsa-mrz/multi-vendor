<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event ConversationStarted
 *
 * Triggered when a new conversation is started.
 * Broadcasts the event to the recipient's private channel.
 */
class ConversationStarted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * The newly started conversation instance.
     *
     * @var \App\Models\Conversation
     */
    public $conversation;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Conversation  $conversation  The conversation that was started.
     * @return void
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel> The private channel for the recipient user.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->conversation->recipient_id),
        ];
    }

    /**
     * Define the data to broadcast with the event.
     *
     * @return array<string, mixed> The data to be sent with the broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'conversation' => [
                'id' => $this->conversation->id,
                'user_id' => $this->conversation->user_id,
                'recipient_id' => $this->conversation->recipient_id,
                'created_at' => $this->conversation->created_at,
            ],
        ];
    }

    /**
     * Customize the broadcast event name.
     *
     * @return string The custom name for the event.
     */
    public function broadcastAs(): string
    {
        return 'conversation.started';
    }
}
