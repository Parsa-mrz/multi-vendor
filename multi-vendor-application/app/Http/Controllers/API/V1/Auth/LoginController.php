<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 *
 * Handles user authentication.
 */
class LoginController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * LoginController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Authenticate a user and generate an access token.
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $user = $this->userRepository->findByEmail($request->email);

        if (!$user || !\password_verify($request->password, $user->password)) {
            return ResponseHelper::error(
                'Invalid credentials',
                null,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $this->userRepository->updateLoginStatus($user);

        $token = $user->createToken('authToken')->plainTextToken;

        return ResponseHelper::success(
            'Login successful',
            ['access_token' => $token, 'token_type' => 'Bearer', 'user' => new UserResource($user->load('profile'))],
            Response::HTTP_OK
        );
    }
}
