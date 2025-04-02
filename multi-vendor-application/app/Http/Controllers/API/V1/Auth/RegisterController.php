<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Service\AuthService;

/**
 * Class RegisterController
 *
 * Handles user registration.
 * This controller processes registration requests, delegates user creation
 * and profile setup to the AuthService, and returns appropriate responses.
 *
 * @package App\Http\Controllers\API\V1\Auth
 */
class RegisterController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * RegisterController constructor.
     *
     * Initializes the controller with the AuthService, which is used
     * to handle user registration logic.
     *
     * @param AuthService $authService The AuthService instance used for user registration.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * This method validates the input data, creates a new user and a profile,
     * and returns a JSON response containing the result of the registration process.
     *
     * @param UserRegisterRequest $request The request containing the data to register the user.
     * @return \Illuminate\Http\JsonResponse A JSON response with success or error message,
     *                                      and the user data if successful.
     */
    public function register(UserRegisterRequest $request)
    {
        $result = $this->authService->registerUser($request->validated());

        if (!$result['success']) {
            return ResponseHelper::error($result['message'], null, $result['status']);
        }

        return ResponseHelper::success(
            $result['message'],
            [$result['data']['user']],
            $result['status']
        );
    }
}
