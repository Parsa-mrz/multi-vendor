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
     * Update the profile with the given ID.
     *
     * @param int $id The ID of the profile to update.
     * @param array $data The data to update the profile with.
     *
     * @return Profile The updated profile instance.
     */
    public function update(int $id, array $data): Profile;

    /**
     * Find a profile by its ID.
     *
     * @param int $id The ID of the profile to find.
     *
     * @return Profile|null The profile if found, or null if not found.
     */
    public function findById(int $id): ?Profile;

    /**
     * Find a profile by the user's ID.
     *
     * @param string $id The ID of the user whose profile to find.
     *
     * @return Profile|null The profile if found, or null if not found.
     */
    public function findByUserId(string $id): ?Profile;

    public function create(array $data): Profile;
}
