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
 * This class provides the implementation for managing user profiles, including finding profiles by ID,
 * updating profiles, and finding profiles by user ID.
 *
 * @package App\Repositories
 */
class ProfileRepository implements ProfileRepositoryInterface
{
    /**
     * Update the profile with the given ID.
     *
     * @param int $id The ID of the profile to update.
     * @param array $data The data to update the profile with.
     *
     * @return Profile The updated profile instance.
     */
    public function update(int $id, array $data): Profile
    {
        // Find the profile by its ID
        $profile = $this->findById($id);

        // Update the profile with the new data
        $profile->update($data);

        return $profile;
    }

    /**
     * Find a profile by its ID.
     *
     * @param int $id The ID of the profile to find.
     *
     * @return Profile|null The profile if found, or null if not found.
     */
    public function findById(int $id): ?Profile
    {
        // Retrieve the profile by ID
        return Profile::find($id);
    }

    /**
     * Find a profile by the user's ID.
     *
     * @param string $id The ID of the user whose profile to find.
     *
     * @return Profile|null The profile if found, or null if not found.
     */
    public function findByUserId(string $id): ?Profile
    {
        // Retrieve the profile by the user_id
        return Profile::where('user_id', $id)->first();
    }
}
