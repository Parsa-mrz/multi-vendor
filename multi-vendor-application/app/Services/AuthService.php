<?php

namespace App\Services;

use App\Events\UserLoggedIn;
use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Services\OtpSenders\EmailOtpSender;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthService
 *
 * This service handles authentication and registration logic for users.
 * It provides functionality to authenticate a user by verifying credentials
 * and generating an access token, as well as registering a new user and
 * creating a corresponding profile.
 */
class AuthService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ProfileRepository
     */
    protected $profileRepository;

    protected $otpService;

    /**
     * AuthService constructor.
     *
     * Initializes the AuthService with the necessary repositories for user
     * and profile management.
     *
     * @param  UserRepository  $userRepository  Repository for managing user data.
     * @param  ProfileRepository  $profileRepository  Repository for managing profile data.
     */
    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository, OtpService $otpService)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
        $this->otpService = $otpService;
    }

    /**
     * Authenticate the user.
     *
     * This method validates the user's credentials (email and password).
     * If the credentials are correct, returns user instance.
     * Otherwise, it returns an error response.
     *
     * @param  string  $email  The email address of the user.
     * @param  string  $password  The password of the user.
     * @return array An array containing the success status, message if successful.
     */
    public function authenticateUser(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user) {
            return ResponseHelper::error(
                'Email not registered.',
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        if (! $user->is_active()) {
            return ResponseHelper::error(
                'Your account is deactivated.please contact administrator.',
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (! Hash::check($password, $user->password)) {
            return ResponseHelper::error(
                'Invalid credentials.',
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $hasVerifiedEmail = $this->userRepository->isEmailVerified($email);
        if (! $hasVerifiedEmail) {
            return ResponseHelper::error(
                'Email is not verified',
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->userRepository->updateLoginStatus($user);

        event(new UserLoggedIn($user));

        return ResponseHelper::success(
            'Login successful',
            ['user' => new UserResource($user->load('profile'))]
        );
    }

    /**
     * Register a new user and create a profile.
     *
     * This method registers a new user by creating a user record and a corresponding
     * profile record. If the registration is successful, it returns the user data
     * along with a success message. If it fails, it returns an error message.
     *
     * @param  array  $data  An array containing the necessary data to register the user.
     * @return array An array containing the success status, message, and user data if successful.
     */
    public function registerUser(array $data): array
    {
        $user = $this->userRepository->create($data);

        $profile = $this->profileRepository->create([
            'user_id' => $user->id,
        ]);

        if ($user && $profile) {
            return ResponseHelper::success(
                'User registered successfully',
                ['user' => new UserResource($user->load('profile'))],
                Response::HTTP_CREATED
            );
        }

        return ResponseHelper::error(
            'Registration failed',
            null,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function sendEmailVerification(array $data)
    {
        return $this->otpService->sendVerificationCode('email', $data['email'], 'email_otp', new EmailOtpSender);
    }
}
