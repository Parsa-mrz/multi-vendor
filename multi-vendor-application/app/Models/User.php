<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * Represents a user in the system. The User model supports authentication, API tokens via Sanctum, and notifications.
 * It also has relationships with the Vendor, Profile, and other models like Order, Message, and Conversation.
 *
 *
 * @property int $id The unique identifier for the user.
 * @property string $email The user's email address.
 * @property string|null $email_verified_at The timestamp when the user's email was verified.
 * @property string $password The user's hashed password.
 * @property bool $is_active Whether the user is active or not.
 * @property string|null $last_login The last login timestamp.
 * @property string $role The user's role (admin, customer, or vendor).
 * @property \Illuminate\Support\Carbon $created_at The timestamp when the user was created.
 * @property \Illuminate\Support\Carbon $updated_at The timestamp when the user was last updated.
 */
class User extends Authenticatable implements FilamentUser, HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'email_verified_at',
        'password',
        'is_active',
        'last_login',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the full name of the user for Filament panel display.
     */
    public function getFilamentName(): string
    {
        return "{$this->profile?->first_name} {$this->profile?->last_name}";
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if the user is a vendor.
     */
    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    /**
     * Check if the user is active.
     */
    public function is_active(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Get the registration duration in days, with a minimum of 1 day.
     */
    public function getRegisterDurationInDays(): string
    {
        return $this->created_at->diffForHumans(['parts' => 1]);
    }

    /**
     * Get the formatted registration date.
     */
    public function getFormattedRegistrationDate(): string
    {
        return $this->created_at->format('Y-m-d');
    }

    /**
     * Get the vendor associated with the user.
     *
     * This method defines a one-to-one relationship between the User and Vendor models.
     */
    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    /**
     * Get the profile associated with the user.
     *
     * This method defines a one-to-one relationship between the User and Profile models.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    /**
     * Get all the orders associated with the user.
     *
     * This method defines a one-to-many relationship between the User and Order models.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Get all the conversations sent by the user.
     *
     * This method defines a one-to-many relationship between the User and Conversation models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentConversations()
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    /**
     * Get all the conversations received by the user.
     *
     * This method defines a one-to-many relationship between the User and Conversation models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedConversations()
    {
        return $this->hasMany(Conversation::class, 'recipient_id');
    }

    /**
     * Get all the messages sent by the user.
     *
     * This method defines a one-to-many relationship between the User and Message models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Determine if the user can access a specific Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
