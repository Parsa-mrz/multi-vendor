<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

/**
 * Class UserRepository
 *
 * Repository for managing interactions with the User model.
 * Implements the UserRepositoryInterface to ensure required methods for user management.
 *
 * @package App\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user record.
     *
     * This method creates a new user in the database using the provided data.
     *
     * @param array $data The data to create the user. Expected to include attributes like name, email, password, etc.
     *
     * @return User The created User instance.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a user by their ID.
     *
     * This method retrieves a user by their unique ID. If the user is not found, it throws a ModelNotFoundException.
     *
     * @param int $id The ID of the user.
     *
     * @return User The found User instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user with the given ID is not found.
     */
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Find a user by their email address.
     *
     * This method retrieves a user associated with the given email address. If no user is found, it throws a ModelNotFoundException.
     *
     * @param string $email The email address of the user.
     *
     * @return User|null The found User instance or null if not found.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no user with the given email is found.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->firstOrFail();
    }

    /**
     * Update a user record.
     *
     * This method updates the details of the user with the specified ID using the provided data.
     *
     * @param int $id The ID of the user to be updated.
     * @param array $data The data to update the user’s record, such as name, email, etc.
     *
     * @return User The updated User instance.
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
     * This method updates the login status of the given user. If the user's last login is null, the user is marked as active.
     * It also updates the `last_login` timestamp.
     *
     * @param User $user The user instance whose login status is to be updated.
     *
     * @return User The updated User instance with the new login status and timestamp.
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
