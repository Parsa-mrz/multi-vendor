<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;

/**
 * Class ProfileRepository
 *
 * Repository for managing interactions with the MyProfile model.
 * Implements the ProfileRepositoryInterface to ensure required methods for profile management.
 *
 * This class provides the implementation for managing user profiles, including finding profiles by ID,
 * updating profiles, and finding profiles by user ID.
 */
class ProfileRepository implements ProfileRepositoryInterface
{
    /**
     * Update the profile with the given ID.
     *
     * @param  int  $id  The ID of the profile to update.
     * @param  array  $data  The data to update the profile with.
     * @return Profile The updated profile instance.
     */
    public function update(int $id, array $data): Profile
    {
        $profile = $this->findById($id);

        $profile->update($data);

        return $profile;
    }

    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    /**
     * Find a profile by its ID.
     *
     * @param  int  $id  The ID of the profile to find.
     * @return Profile|null The profile if found, or null if not found.
     */
    public function findById(int $id): ?Profile
    {
        return Profile::find($id);
    }

    /**
     * Find a profile by the user's ID.
     *
     * @param  string  $id  The ID of the user whose profile to find.
     * @return Profile|null The profile if found, or null if not found.
     */
    public function findByUserId(string $id): ?Profile
    {
        return Profile::where('user_id', $id)->first();
    }
}
