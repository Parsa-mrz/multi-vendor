<?php

namespace App\Repositories;

use App\Interfaces\VendorRepositoryInterface;
use App\Models\Vendor;

class VendorRepository implements VendorRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $data): Vendor
    {
        return Vendor::create($data);
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): Vendor
    {
        return Vendor::findOrFail($id);
    }
    /**
     * @inheritDoc
     */
    public function findByUserId(int $userId): Vendor
    {
        return Vendor::where('user_id', $userId)->firstOrFail();
    }
}
