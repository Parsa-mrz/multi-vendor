<?php

namespace App\Services;

use App\Events\UserLoggedIn;
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
    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository,OtpService $otpService)
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

        if (! $user || ! Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'status' => Response::HTTP_UNAUTHORIZED,
            ];
        }

        $hasVerifiedEmail = $this->userRepository->isEmailVerified ($email);
        if(!$hasVerifiedEmail){
            return [
                'success' => false,
                'message' => 'Email is not verified',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        $this->userRepository->updateLoginStatus($user);

        event(new UserLoggedIn($user));

        return [
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => new UserResource($user->load('profile')),
            ],
            'status' => Response::HTTP_OK,
        ];
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
            return [
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => new UserResource($user->load('profile')),
                ],
                'status' => Response::HTTP_CREATED,
            ];
        }

        return [
            'success' => false,
            'message' => 'Registration failed',
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ];
    }

    public function  sendEmailVerification(array $data){
        $result = $this->otpService->sendVerificationCode ('email',$data['email'],'email_otp',new EmailOtpSender());
        return [
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => null,
            'status' => $result['status'],
        ];
    }

}
