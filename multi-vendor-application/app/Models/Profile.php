<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Profile
 *
 * Represents the profile associated with a user in the system.
 * The Profile model supports polymorphic relationships through the `profileable` method.
 */
class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'first_name', 'last_name', 'phone_number'];

    /**
     * Get the user that owns the profile.
     *
     * This method defines a one-to-many inverse relationship between the Profile and User models.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
