<?php

namespace App\Interfaces;

use App\Models\Vendor;

interface VendorRepositoryInterface
{
    public function create(array $data): Vendor;
    public function findById(int $id): Vendor;
    public function findByUserId(int $userId): Vendor;
}
