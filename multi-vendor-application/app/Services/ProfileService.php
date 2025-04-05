<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\Profile;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ProfileService
{
    protected $profileRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function updateProfile(int $id, array $data): Profile|array
    {
        $profile = $this->profileRepository->findById($id);

        if (! $profile) {

            return ResponseHelper::error(
                'MyProfile not found.',
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        if (Gate::denies('update', $profile)) {
            return ResponseHelper::error(
                'You are not authorized to update this profile.',
                null,
                Response::HTTP_FORBIDDEN
            );
        }

        return $this->profileRepository->update($id, $data);
    }

    public function getProfileById(int $id): Profile|array
    {
        $profile = $this->profileRepository->findById($id);

        if ($profile && Gate::denies('view', $profile)) {
            return ResponseHelper::error(
                'You are not authorized to update this profile.',
                null,
                Response::HTTP_FORBIDDEN
            );
        }

        return $profile;
    }

    public function getProfileByUserId(string $id): Profile|array
    {
        $profile = $this->profileRepository->findByUserId($id);

        if ($profile && Gate::denies('view', $profile)) {
            return ResponseHelper::error(
                'You are not authorized to update this profile.',
                null,
                Response::HTTP_FORBIDDEN
            );
        }

        return $profile;
    }
}
