<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
        Log::debug('MessageSent event initialized', [
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'body' => $this->message->body,
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {

        try {
            $channel = new Channel('conversation.' . $this->message->conversation_id);
            Log::debug('Broadcasting on channel', ['channel' => $channel->name]);
            return [
                new Channel('conversation.' . $this->message->conversation_id)
            ];
        } catch (\Exception $e) {
            Log::error('Failed to determine broadcast channel', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function broadcastWith()
    {
        try {
            $data = [
                'id' => $this->message->id,
                'body' => $this->message->body,
                'sender_id' => $this->message->sender_id,
                'created_at' => $this->message->created_at->toDateTimeString(),
            ];
            Log::debug('Broadcast data prepared', $data);
            return $data;
        } catch (\Exception $e) {
            Log::error('Failed to prepare broadcast data', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
