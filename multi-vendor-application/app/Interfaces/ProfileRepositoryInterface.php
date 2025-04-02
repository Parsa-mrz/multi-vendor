<?php

namespace App\Interfaces;

use App\Models\Profile;

/**
 * Interface ProfileRepositoryInterface
 *
 * Defines the contract for a Profile repository.
 * This interface will be implemented by any class that manages user profiles.
 */
interface ProfileRepositoryInterface
{
    /**
     * Create a new profile.
     *
     * This method accepts profile data and creates a new profile entry in the database.
     *
     * @param  array  $data  The profile data, typically including user-specific details.
     * @return Profile The created Profile instance.
     */
    public function create(array $data): Profile;

    /**
     * Find a profile by user ID.
     *
     * This method retrieves a profile associated with a specific user by their ID.
     * If no profile is found, it returns null.
     *
     * @param  int  $userId  The user ID to search for.
     * @return Profile|null The Profile instance if found, otherwise null.
     */
    public function findByUserId(int $userId): ?Profile;
}
