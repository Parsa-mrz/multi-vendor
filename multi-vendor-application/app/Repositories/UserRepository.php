<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

/**
 * Repository class for managing User model interactions.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user record.
     *
     * @param array $data The data to create the user.
     * @return User The created user instance.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id The ID of the user.
     * @return User The found user instance.
     */
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Find a user by their email address.
     *
     * @param string $email The email address of the user.
     * @return User|null The found user instance or null if not found.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->firstOrFail();
    }

    /**
     * Update a user record.
     *
     * @param int $id The ID of the user.
     * @param array $data The data to update.
     * @return User The updated user instance.
     */
    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);
        $user->update($data);
        return $user;
    }

    /**
     * Update a user's login status.
     *
     * @param User $user The user instance.
     * @return User The updated user instance.
     */
    public function updateLoginStatus(User $user): User
    {
        $data = [
            'last_login' => now(),
        ];

        if (is_null($user->last_login)) {
            $data['is_active'] = true;
        }

        $user->update($data);
        return $user;
    }
}
