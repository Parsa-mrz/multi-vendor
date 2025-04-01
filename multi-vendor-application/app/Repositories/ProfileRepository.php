<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;

/**
 * Repository class for managing Profile model interactions.
 */
class ProfileRepository implements ProfileRepositoryInterface
{
    /**
     * Create a new profile record.
     *
     * @param array $data The data to create the profile.
     * @return Profile The created profile instance.
     */
    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    /**
     * Find a profile by user ID.
     *
     * @param int $userId The ID of the user.
     * @return Profile|null The found profile instance or null if not found.
     */
    public function findByUserId(int $userId): ?Profile
    {
        return Profile::where('user_id', $userId)->first();
    }
}
