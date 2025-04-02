<?php

namespace App\Interfaces;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 *
 * Defines the contract for a User repository.
 * This interface specifies the required methods for managing users.
 *
 * @package App\Interfaces
 */
interface UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * This method accepts user data and creates a new user entry in the database.
     *
     * @param array $data The user data, typically including name, email, and password.
     *
     * @return User The created User instance.
     */
    public function create(array $data): User;

    /**
     * Find a user by email.
     *
     * This method retrieves a user associated with the given email address.
     * If no user is found, it returns null.
     *
     * @param string $email The email address to search for.
     *
     * @return User|null The User instance if found, otherwise null.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user by ID.
     *
     * This method retrieves a user associated with the given user ID.
     * The user is returned as a User model.
     *
     * @param int $id The user ID to search for.
     *
     * @return User The User instance if found.
     */
    public function findById(int $id): User;

    /**
     * Update a user's information.
     *
     * This method updates the user’s details in the database based on the provided ID and data.
     *
     * @param int $id The user ID.
     * @param array $data The update data, typically including fields like name, email, etc.
     *
     * @return User The updated User instance.
     */
    public function update(int $id, array $data): User;

    /**
     * Update a user's login status.
     *
     * This method updates the login status of the given user.
     * It could involve marking the user as logged in or logged out.
     *
     * @param User $user The user instance whose login status is to be updated.
     *
     * @return User The updated User instance with the new login status.
     */
    public function updateLoginStatus(User $user): User;
}
