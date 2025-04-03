<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Service\AuthService;
use App\Service\OtpService;
use Illuminate\Http\Request;

/**
 * Class AuthController
 *
 * Base authentication controller that provides shared authentication-related services.
 */
class AuthController extends Controller
{
    /**
     * @var AuthService Handles authentication logic.
     */
    protected $authService;

    /**
     * @var OtpService Handles OTP-related operations.
     */
    protected $otpService;

    /**
     * @var UserRepository Manages user-related database operations.
     */
    protected $userRepository;

    /**
     * AuthController constructor.
     *
     * Initializes the controller with authentication, OTP, and user repository services.
     *
     * @param AuthService $authService The authentication service instance.
     * @param OtpService $otpService The OTP service instance.
     * @param UserRepository $userRepository The user repository instance.
     */
    public function __construct(AuthService $authService, OtpService $otpService,UserRepository $userRepository)
    {
        $this->authService = $authService;
        $this->otpService = $otpService;
        $this->userRepository = $userRepository;
    }
}
