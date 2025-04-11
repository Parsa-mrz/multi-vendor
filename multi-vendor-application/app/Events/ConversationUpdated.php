<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event ConversationUpdated
 *
 * Triggered when a conversation is updated.
 * Broadcasts the update to both participants of the conversation.
 */
class ConversationUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * The updated conversation instance.
     *
     * @var \App\Models\Conversation
     */
    public $conversation;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Conversation  $conversation  The conversation that was updated.
     * @return void
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel> Private channels for both users in the conversation.
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.'.$this->conversation->user_id),
            new PrivateChannel('user.'.$this->conversation->recipient_id),

        ];
    }

    /**
     * Customize the broadcast event name.
     *
     * @return string The name of the broadcasted event.
     */
    public function broadcastAs(): string
    {
        return 'conversation.updated';
    }
}
