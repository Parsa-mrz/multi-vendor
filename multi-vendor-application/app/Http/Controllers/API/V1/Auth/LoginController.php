<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Service\AuthService;

/**
 * Class LoginController
 *
 * Handles user authentication via login.
 * This controller processes login requests, delegates authentication to
 * the AuthService, and returns appropriate responses.
 *
 * @package App\Http\Controllers\API\V1\Auth
 */
class LoginController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;


    /**
     * LoginController constructor.
     *
     * Initializes the controller with the AuthService, which is used
     * to handle the authentication logic.
     *
     * @param AuthService $authService The AuthService instance used for authentication.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    /**
     * Handle the login request.
     *
     * This method validates the provided credentials (email and password),
     * and if they are correct, it generates an authentication token.
     * It returns a JSON response containing the result of the authentication.
     *
     * @param UserLoginRequest $request The request containing the user's email and password.
     * @return \Illuminate\Http\JsonResponse A JSON response with success or error message,
     *                                      the authentication token if successful, and the appropriate HTTP status.
     */
    public function login(UserLoginRequest $request)
    {
        $result = $this->authService->authenticateUser($request->email, $request->password);

        if (!$result['success']) {
            return ResponseHelper::error($result['message'], null, $result['status']);
        }

        return ResponseHelper::success($result['message'], $result['data'], $result['status']);
    }
}
