<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 *
 * Represents a message within a conversation between two users.
 * A message is associated with a sender, a conversation, and can have a read status.
 *
 *
 * @property int $id
 * @property int $conversation_id The ID of the conversation that the message belongs to.
 * @property int $sender_id The ID of the user who sent the message.
 * @property string $body The content of the message.
 * @property bool $read Indicates whether the message has been read.
 * @property \Illuminate\Support\Carbon $created_at Timestamp when the message was created.
 * @property \Illuminate\Support\Carbon $updated_at Timestamp when the message was last updated.
 */
class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['conversation_id', 'sender_id', 'body', 'read'];

    /**
     * Get the conversation that the message belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender of the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
