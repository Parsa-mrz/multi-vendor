<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;

/**
 * Class ProfileRepository
 *
 * Repository for managing interactions with the Profile model.
 * Implements the ProfileRepositoryInterface to ensure required methods for profile management.
 *
 * @package App\Repositories
 */
class ProfileRepository implements ProfileRepositoryInterface
{
    /**
     * Create a new profile record.
     *
     * This method creates a new profile in the database using the provided data.
     *
     * @param array $data The data to create the profile. Expected to include attributes like user_id, bio, etc.
     *
     * @return Profile The created Profile instance.
     */
    public function create(array $data): Profile
    {
        return Profile::create($data);
    }


    /**
     * Find a profile by user ID.
     *
     * This method retrieves a profile associated with the given user ID.
     * If no profile is found for that user, it returns null.
     *
     * @param int $userId The user ID to search for.
     *
     * @return Profile|null The Profile instance if found, otherwise null.
     */
    public function findByUserId(int $userId): ?Profile
    {
        return Profile::where('user_id', $userId)->first();
    }
}
