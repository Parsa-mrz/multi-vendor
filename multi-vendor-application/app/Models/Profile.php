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
 *
 *
 * @property int $id The unique identifier for the profile.
 * @property int $user_id The ID of the user this profile is associated with.
 * @property string $first_name The user's first name.
 * @property string $last_name The user's last name.
 * @property string|null $phone_number The user's phone number.
 * @property string|null $address The user's address.
 * @property \Illuminate\Support\Carbon $created_at The timestamp when the profile was created.
 * @property \Illuminate\Support\Carbon $updated_at The timestamp when the profile was last updated.
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
    protected $fillable = ['user_id', 'first_name', 'last_name', 'phone_number', 'address'];

    /**
     * Get the user that owns the profile.
     *
     * This method defines a one-to-many inverse relationship between the Profile and User models.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
