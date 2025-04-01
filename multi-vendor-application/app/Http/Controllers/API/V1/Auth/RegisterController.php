<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterController
 *
 * Handles user registration.
 */
class RegisterController extends Controller
{
    /**
    * @var UserRepository
    */
    protected $userRepository;

   /**
    * @var ProfileRepository
    */
    protected $profileRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     */
    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }


    /**
     * Register a new user.
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        $user = $this->userRepository->create($request->validated());

        $this->profileRepository->create([
            'user_id' => $user->id,
            'profileable_id' => $user->id,
            'profileable_type' => User::class,
        ]);

        return ResponseHelper::success(
            'User registered successfully',
            [new UserResource($user->load('profile'))],
            Response::HTTP_CREATED
        );
    }
}
