<?php

namespace App\Providers;

use App\Models\Profile;
use App\Policies\ProfilePolicy;
use App\Repositories\CacheRepository;
use App\Repositories\UserRepository;
use App\Services\OtpService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository();
        });
        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService($app->make(CacheRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Profile::class, ProfilePolicy::class);
    }
}
