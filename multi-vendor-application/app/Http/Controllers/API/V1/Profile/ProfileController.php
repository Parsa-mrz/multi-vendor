<?php

namespace App\Http\Controllers\API\V1\Profile;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class ProfileController extends Controller
{
    protected ProfileRepository $profileRepository;

    public function __construct (ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return ResponseHelper::unAvailable ();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $profile = $this->profileRepository->findById ($id);

        if (!$profile) {
            return ResponseHelper::error ('Profile not found',null,Response::HTTP_NOT_FOUND);
        }

        if (Gate::denies('view', $profile)) {
            return ResponseHelper::error ('Unauthorized',null,Response::HTTP_FORBIDDEN);
        }

        return ResponseHelper::success (
            'Profile found',
            ['profile' => new ProfileResource($profile) ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request,int $id)
    {
        $profile = $this->profileRepository->update($id,$request->validated());

        if (!$profile) {
            return ResponseHelper::error ('Profile not found',null,Response::HTTP_NOT_FOUND);
        }

        return ResponseHelper::success (
            'Profile updated successfully',
            ['profile' => new ProfileResource($profile)],
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return ResponseHelper::unAvailable ();
    }
}
