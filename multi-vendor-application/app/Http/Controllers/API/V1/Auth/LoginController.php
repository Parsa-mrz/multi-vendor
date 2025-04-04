<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\UserLoginRequest;

/**
 * Class LoginController
 *
 * Handles user authentication via login.
 * This controller processes login requests, delegates authentication to
 * the AuthService, and returns appropriate responses.
 */
class LoginController extends AuthController
{


    /**
     * Handle the login request.
     *
     * This method validates the provided credentials (email and password),
     * and if they are correct, it generates an authentication token.
     * It returns a JSON response containing the result of the authentication.
     *
     * @param  UserLoginRequest  $request  The request containing the user's email and password.
     * @return \Illuminate\Http\JsonResponse A JSON response with success or error message,
     *                                       the authentication token if successful, and the appropriate HTTP status.
     */
    public function login(UserLoginRequest $request)
    {
        $result = $this->authService->authenticateUser($request->email, $request->password);

        if (! $result['success']) {
            return ResponseHelper::error($result['message'], null, $result['status']);
        }

        return ResponseHelper::success($result['message'], $result['data'], $result['status']);
    }
}
