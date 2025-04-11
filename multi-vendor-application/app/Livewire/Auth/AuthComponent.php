<?php

namespace App\Livewire\Auth;

use App\Helpers\SweetAlertHelper;
use App\Livewire\BaseComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;

use function is_null;

class AuthComponent extends BaseComponent
{
    public $email = '';

    public $password = '';

    public $message = '';

    public $code = '';

    public $showOtpForm = false;

    public $originalEmail = '';

    protected function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|max:255',
            'password' => 'required',
            'code' => 'nullable|digits:4',
        ];
    }

    protected function messages()
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Enter a valid email address.',
            'password.required' => 'The password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'code.digits' => 'OTP must be 6 digits.',
        ];
    }

    public function submit()
    {
        $this->email = strtolower(trim($this->email));
        $this->validateOnly('email');
        $this->validateOnly('password');

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
        if (! $result['success']) {
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
        $this->validateOnly('code');

        $otpResult = $this->otpService->verifyVerificationCode(
            'email',
            $this->originalEmail,
            $this->code,
            'email_otp'
        );

        if (! $otpResult['success']) {
            $this->message = $otpResult['message'];

            return false;
        }

        $result = $this->authService->registerUser([
            'email' => $this->email,
            'password' => $this->password,
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
        if ($result['status'] === 422) {
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
        $user = $this->userRepository->findByEmail(strtolower($result['data']['user']['email']));
        Auth::login($user);

        $this->message = $result['message'];
        $this->reset(['email', 'password', 'code', 'showOtpForm', 'originalEmail']);
        if(Session::has ('login')){
            $this->redirect (Session::pull('login.route'));
            Session::forget('login');
        }else{
            $this->redirect('/dashboard');
        }
    }

    #[Title('Login/Register')]
    public function render()
    {
        if(Session::has('login')){
            SweetAlertHelper::warning($this, session('login.message'),'');
        }
        return view('livewire.auth.auth-component');
    }
}
