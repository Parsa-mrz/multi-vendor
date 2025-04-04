<?php

namespace App\Livewire;

use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\OtpService;
use Livewire\Component;
use App\Traits\TokenManagementTrait;


use function config;

/**
 * Class BaseComponent
 *
 * This abstract class serves as a base for all Livewire components in the application.
 * It sets a default layout and initializes a configurable API URL.
 */
abstract class BaseComponent extends Component
{
    use TokenManagementTrait;

    /**
     * The default layout for the component.
     *
     * @var string
     */
    protected $layout = 'components.layouts.app';

    /**
     * The API URL retrieved from the application configuration.
     *
     * @var string
     */
    public $apiUrl;

    protected UserRepository $userRepository;
    protected OtpService $otpService;
    protected AuthService $authService;
    public function boot(UserRepository $userRepository,OtpService $otpService = null, AuthService $authService = null)
    {
        $this->userRepository = $userRepository;
        $this->otpService = $otpService;
        $this->authService = $authService;
    }

    /**
     * Lifecycle hook that runs when the component is mounted.
     * It initializes the API URL from the application configuration.
     *
     * @return void
     */
    public function mount()
    {
        $this->apiUrl = config('app.api_url');
    }

    /**
     * Abstract render method to be implemented by child components.
     *
     * @return \Illuminate\View\View
     */
    abstract public function render();
}
