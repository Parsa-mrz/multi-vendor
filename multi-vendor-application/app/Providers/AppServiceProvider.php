<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use App\Interfaces\CartRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Profile;
use App\Policies\ProductPolicy;
use App\Policies\ProfilePolicy;
use App\Repositories\ProductRepository;
use App\Repositories\CartRepository;
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

        // Bind CartRepositoryInterface to CartRepository
        $this->app->singleton(CartRepositoryInterface::class, function ($app) {
            return new CartRepository($app->make(ProductRepositoryInterface::class));
        });

        // Bind ProductRepositoryInterface to ProductRepository
        $this->app->singleton(ProductRepositoryInterface::class, function ($app) {
            return new ProductRepository();
        });
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
