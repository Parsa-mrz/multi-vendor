<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation
 *
 * Represents a conversation between two users.
 * A conversation involves a user and a recipient and may contain multiple messages.
 *
 *
 * @property int $id
 * @property int $recipient_id The ID of the user who is the recipient in the conversation.
 * @property int $user_id The ID of the user who initiates the conversation.
 * @property \Illuminate\Support\Carbon $created_at Timestamp when the conversation was created.
 * @property \Illuminate\Support\Carbon $updated_at Timestamp when the conversation was last updated.
 */
class Conversation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recipient_id',
        'user_id',
    ];

    /**
     * Get the user who started the conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the recipient of the conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the messages associated with the conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
