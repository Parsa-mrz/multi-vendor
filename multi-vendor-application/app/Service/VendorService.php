<?php

namespace App\Service;

use App\Exceptions\VendorAlreadyRegisteredException;
use App\Models\User;
use App\Repositories\VendorRepository;

class VendorService
{
    /**
     * @var VendorRepository
     */
    protected $vendorRepository;

    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }


    public function registerAsVendor(User $user, array $vendorData): User
    {
        if ($user->hasRole('vendor') || $this->vendorRepository->findByUserId($user->id)) {
            throw new VendorAlreadyRegisteredException('You are already registered as a vendor.');
        }

        $vendorData['user_id'] = $user->id;
        $this->vendorRepository->create($vendorData);

        return $user;
    }
}
