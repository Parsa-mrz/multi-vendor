<?php

namespace App\Livewire\Auth;

use App\Livewire\BaseComponent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Title;

/**
 * Class AuthComponent
 *
 * Handles user authentication, including login and automatic registration if login fails due to missing credentials.
 *
 * @package App\Livewire\Auth
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

        if ($loginResponse->successful()) {
            $this->handleSuccessfulLogin($loginResult);
        } else {
            $errorMessage = $loginResult['message'] ?? 'Login failed.';
            if (str_contains(strtolower($errorMessage), 'user not found') || str_contains(strtolower($errorMessage), 'invalid credentials')) {
                $registerResponse = Http::post("{$this->baseUrl}/api/v1/register", [
                    'email' => $this->email,
                    'password' => $this->password,
                ]);

                $registerResult = $registerResponse->json();

                if ($registerResponse->successful()) {
                    $loginRetryResponse = Http::post("{$this->baseUrl}/api/v1/login", [
                        'email' => $this->email,
                        'password' => $this->password,
                    ]);

                    $loginRetryResult = $loginRetryResponse->json();

                    if ($loginRetryResponse->successful()) {
                        $this->handleSuccessfulLogin($loginRetryResult);
                    } else {
                        $this->message = 'Registration succeeded, but login failed: ' . ($loginRetryResult['message'] ?? 'Unknown error.');
                    }
                } else {
                    $this->message = $registerResult['message'] ?? 'Registration failed.';
                }
            } else {
                $this->message = $errorMessage;
            }
        }
    }

    /**
     * Handles successful login by storing the authentication token and redirecting the user.
     *
     * @param array $result The response data from the API.
     * @return void
     */
    private function handleSuccessfulLogin($result)
    {
        session(['token' => $result['data']['token'] ?? '']);

        $userData = $result['data']['user'] ?? null;
        //todo: find better solution
        $user = User::find($userData['id'])->first();
        Auth::login($user);

        $this->message = $result['message'];
        $this->reset(['email', 'password']);
        $this->redirect('/dashboard');
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
