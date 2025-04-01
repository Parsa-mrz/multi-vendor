<?php

namespace App\Interfaces;

use App\Models\User;

/**
 * Interface for User Repository.
 */
interface UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param array $data The user data.
     * @return User
     */
    public function create(array $data): User;

    /**
     * Find a user by email.
     *
     * @param string $email The email address.
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
    /**
     * Find a user by ID.
     *
     * @param int $id The user ID.
     * @return User
     */
    public function findById(int $id): User;

    /**
     * Update a user's information.
     *
     * @param int $id The user ID.
     * @param array $data The update data.
     * @return User
     */
    public function update(int $id, array $data): User;

    /**
     * Update a user's login status.
     *
     * @param User $user The user instance.
     * @return User
     */
    public function updateLoginStatus(User $user): User;
}
