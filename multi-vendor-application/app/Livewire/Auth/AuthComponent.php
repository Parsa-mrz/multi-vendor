<?php

namespace App\Livewire\Auth;

use App\Livewire\BaseComponent;
use App\Models\User;
use App\Service\OtpSenders\EmailOtpSender;
use App\Service\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Title;
use function is_null;
use function session;

class AuthComponent extends BaseComponent
{
    public $email = '';
    public $password = '';
    public $message = '';
    public $code = '';
    public $showOtpForm = false;
    public $originalEmail = '';

    public function submit()
    {
        $this->message = '';
        $this->originalEmail = $this->email;

        $loginResponse = $this->loginUser();

        if ($loginResponse) {
            $this->handleLoginOrVerification($loginResponse);
        } else {
            $this->registerUser();
        }
    }

    private function loginUser()
    {
        $response = Http::post("{$this->apiUrl}/api/v1/login", [
            'email' => $this->originalEmail,
            'password' => $this->password,
        ]);

        return $response->successful() ? $response->json() : null;
    }

    private function registerUser()
    {
        $response = Http::post("{$this->apiUrl}/api/v1/register", [
            'email' => $this->originalEmail,
            'password' => $this->password,
        ]);

        $result = $response->json();
        if ($response->successful()) {
            $this->sendEmailOtp($this->originalEmail);
            $this->showOtpForm = true;
            $this->message = 'Registration successful! Please enter the OTP sent to your email.';
        } else {
            $this->message = $result['message'] ?? 'Registration failed.';
        }
    }

    public function verifyOtp()
    {
        $verificationData = [
            'type' => 'email',
            'value' => $this->originalEmail,
            'code' => (int) $this->code,
            'password' => $this->password,
        ];

        $response = Http::post("{$this->apiUrl}/api/v1/verify", $verificationData);

        if ($response->successful()) {
            $this->reLoginUser();
        } else {
            $this->message = 'OTP verification failed. Please check your code.';
        }
    }

    private function reLoginUser()
    {
        $loginResponse = $this->loginUser();
        if ($loginResponse) {
            $this->handleSuccessfulLogin($loginResponse);
        } else {
            $this->message = 'Login failed after OTP verification.';
        }
    }

    private function handleLoginOrVerification($result)
    {
        $userData = $result['data']['user'] ?? null;
        $user = User::where('email', $userData['email'])->first();

        if (is_null($user->email_verified_at)) {
            $this->sendEmailOtp($this->originalEmail);
            $this->showOtpForm = true;
            $this->message = 'Please enter the OTP sent to your email to verify your account.';
        } else {
            $this->handleSuccessfulLogin($result);
        }
    }

    private function handleSuccessfulLogin($result)
    {
        $userData = $result['data']['user'] ?? null;
        $user = User::where('email', $userData['email'])->first();
        Auth::login($user);

        session(['token' => $result['data']['token'] ?? '']);
        $this->message = $result['message'];
        $this->reset(['email', 'password', 'code', 'showOtpForm', 'originalEmail']);
        $this->redirect('/dashboard');
    }

    private function sendEmailOtp(string $email)
    {
        $otpService = app(OtpService::class);
        $otpService->sendVerificationCode('email', $email, 'email_otp', new EmailOtpSender());
    }

    #[Title('Login/Register')]
    public function render()
    {
        return view('livewire.auth.auth-component');
    }
}
