<?php

namespace App\Interfaces;

use App\Models\Profile;

/**
 * Interface for Profile Repository.
 */
interface ProfileRepositoryInterface
{
    /**
     * Create a new profile.
     *
     * @param array $data The profile data.
     * @return Profile
     */
    public function create(array $data): Profile;

    /**
     * Find a profile by user ID.
     *
     * @param int $userId The user ID.
     * @return Profile|null
     */
    public function findByUserId(int $userId): ?Profile;
}
