<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * Represents a user in the system.
 * The User model supports authentication, API tokens via Sanctum, and notifications.
 * It also has relationships with the Vendor and Profile models.
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be set using mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'is_active',
        'last_login',
        'role'
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
            'is_active' => 'boolean'
        ];
    }

    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }


    /**
     * Get the vendor associated with the user.
     *
     * This method defines a one-to-one relationship between the User and Vendor models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
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
}
