<?php

namespace App\Livewire\Auth;

use App\Livewire\BaseComponent;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Service\OtpSenders\EmailOtpSenderInterface;
use App\Service\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use function app;
use function dd;
use function is_null;
use function session;

/**
 * Class AuthComponent
 *
 * Handles user authentication, including login and automatic registration if login fails due to missing credentials.
 */
class AuthComponent extends BaseComponent
{
    /**
     * User's email address.
     *
     * @var string
     */
    public $email = '';

    /**
     * User's password.
     *
     * @var string
     */
    public $password = '';

    /**
     * Message to display authentication status.
     *
     * @var string
     */
    public $message = '';

    public $otp = '';

    public $showOtpForm = false;

    /**
     * Handles the authentication process.
     *
     * - Attempts login with the provided credentials.
     * - If login fails due to missing credentials, tries to register the user and then reattempts login.
     * - Stores the authentication token in the session if login is successful.
     *
     * @return void
     */
    public function submit()
    {
        $this->message = '';

        $loginResponse = Http::post("{$this->apiUrl}/api/v1/login", [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $loginResult = $loginResponse->json();
        if (!is_null ($loginResult)) {
            $this->handleLoginOrVerification($loginResult);
        } else {
                $registerResponse = Http::post("{$this->apiUrl}/api/v1/register", [
                    'email' => $this->email,
                    'password' => $this->password,
                ]);

                $registerResult = $registerResponse->json();

                if ($registerResponse->successful()) {
                    $this->sendEmailOtp($this->email);
                    $this->showOtpForm = true;
                    $this->message = 'Registration successful! Please enter the OTP sent to your email.';
                } else {
                    $this->message = $registerResult['message'] ?? 'Registration failed.';
                }
        }
    }

    /**
     * @return void
     */
    public function verifyOtp()
    {
        $otpService = app(OtpService::class);
        $result = $otpService->verifyVerificationCode('email', $this->email, (int) $this->otp, 'email_otp');

        if ($result['status']) {
            $loginResponse = Http::post("{$this->apiUrl}/api/v1/login", [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            $loginResult = $loginResponse->json();
            Log::info($loginResult);
            if ($loginResponse->successful()) {
                $this->handleSuccessfulLogin($loginResult);
            } else {
                $this->message = 'Login failed after OTP verification.';
            }
        } else {
            $this->message = $result['message'];
        }
    }

    /**
     * Handles successful login by storing the authentication token and redirecting the user.
     *
     * @param  array  $result  The response data from the API.
     * @return void
     */
    private function handleSuccessfulLogin($result)
    {

        $userData = $result['data']['user'] ?? null;
        $user = User::where('email', $userData['email'])->first();
        Auth::login($user);

        session(['token' => $result['data']['token'] ?? '']);
        $this->message = $result['message'];
        $this->reset(['email', 'password']);
        $this->redirect('/dashboard');
    }

    private function handleLoginOrVerification(array $result)
    {
        $userData = $result['data']['user'] ?? null;
        $user = User::where('email', $userData['email'])->first();

        if (is_null($user->email_verified_at)) {
            $this->sendEmailOtp($this->email);
            $this->showOtpForm = true;
            $this->message = 'Please enter the OTP sent to your email to verify your account.';
        } else {
            $this->handleSuccessfulLogin($result);
        }
    }

    private function sendEmailOtp(string $email)
    {
        $otpService = app(OtpService::class);
        $otpService->sendVerificationCode('email', $email, 'email_otp', new EmailOtpSenderInterface());
    }

    /**
     * Renders the authentication component view.
     *
     * @return \Illuminate\View\View
     */
    #[Title('Login/Register')]
    public function render()
    {
        return view('livewire.auth.auth-component');
    }
}
