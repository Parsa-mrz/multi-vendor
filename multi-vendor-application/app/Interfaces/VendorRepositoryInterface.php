<?php

namespace App\Interfaces;

use App\Models\Vendor;

/**
 * Interface VendorRepositoryInterface
 *
 * Defines the contract for managing vendor entities within the application.
 * The interface includes methods for creating a vendor, and retrieving
 * vendors by their ID or associated user ID.
 */
interface VendorRepositoryInterface
{
    /**
     * Create a new vendor.
     *
     * @param  array  $data  The data required to create a new vendor (e.g., name, user_id, etc.).
     * @return \App\Models\Vendor The created vendor instance.
     */
    public function create(array $data): Vendor;

    /**
     * Find a vendor by its ID.
     *
     * @param  int  $id  The ID of the vendor to retrieve.
     * @return \App\Models\Vendor The vendor instance associated with the given ID.
     */
    public function findById(int $id): Vendor;

    /**
     * Find a vendor by the associated user ID.
     *
     * @param  int  $userId  The user ID associated with the vendor to retrieve.
     * @return \App\Models\Vendor The vendor instance associated with the given user ID.
     */
    public function findByUserId(int $userId): Vendor;
}
