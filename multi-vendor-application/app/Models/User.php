<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * Represents a user in the system.
 * The User model supports authentication, API tokens via Sanctum, and notifications.
 * It also has relationships with the Vendor and Profile models.
 */
class User extends Authenticatable implements FilamentUser, HasName
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be set using mass assignment.
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
     * These attributes will not be included when the model is serialized.
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
     * These attributes will be cast to the specified types.
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

    public function getFilamentName(): string
    {
        return "{$this->profile?->first_name} {$this->profile?->last_name}";
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    /**
     * Get the registration duration in days, with a minimum of 1 day.
     *
     * @return int
     */
    public function getRegisterDurationInDays(): int
    {
        $days = $this->created_at->diffInHours(now()) / 24;
        return $days < 1 ? 1 : round($days);
    }

    /**
     * Get the formatted registration date.
     *
     * @return string
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
        return $this->hasOne(Vendor::class);
    }

    /**
     * Get the profile associated with the user.
     *
     * This method defines one-to-one relationship between the User and Profile models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
