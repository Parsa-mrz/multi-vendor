<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use App\Models\Product;
use App\Models\Profile;
use App\Policies\ProductPolicy;
use App\Policies\ProfilePolicy;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Profile::class, ProfilePolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
    }
}
