<?php

namespace App\Livewire\Auth;

use App\Helpers\ResponseHelper;
use App\Livewire\BaseComponent;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\OtpSenders\EmailOtpSender;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use function app;
use function dd;
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
        $result = $this->authService->authenticateUser($this->email, $this->password);
        if (! $result['status']) {
            return false;
        }

        return $result;
    }

    private function registerUser()
    {
        $result = $this->authService->sendEmailVerification(['email' => $this->email]);

        if (! $result['success']) {
            $this->message = $result['message'] ?? 'Verification failed.';
            return false;
        }

        $this->handleLoginOrVerification($result);
    }

    public function verifyOtp()
    {
        $otpResult = $this->otpService->verifyVerificationCode('email', $this->originalEmail, $this->code, 'email_otp');

        if(!$otpResult['success']){
            $this->message = $otpResult['message'];
            return false;
        }

        $result = $this->authService->registerUser([
            "email" => $this->email,
            "password" => $this->password
        ]);

        if (! $result['success']) {
            $this->message = $result['message'] ?? 'Register failed.';
            return false;
        }

        $this->handleLoginOrVerification($result);

        return true;
    }

    private function handleLoginOrVerification($result)
    {
        if($result['status'] === 422){
            $this->message = $result['message'];
            return false;
        }
        if (is_null($result['data'])) {
            $this->showOtpForm = true;
            $this->message = 'Please enter the OTP sent to your email to verify your account.';
        } else {
            $this->handleSuccessfulLogin($result);
        }
    }

    private function handleSuccessfulLogin($result)
    {
        $user = $this->userRepository->findByEmail($result['data']['user']['email']);
        Auth::login($user);

        $this->message = $result['message'];
        $this->reset(['email', 'password', 'code', 'showOtpForm', 'originalEmail']);
        $this->redirect('/dashboard');
    }

    #[Title('Login/Register')]
    public function render()
    {
        return view('livewire.auth.auth-component');
    }
}
