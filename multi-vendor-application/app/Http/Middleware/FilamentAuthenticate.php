<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as Filament;
use Illuminate\Auth\AuthenticationException;

/**
 * Custom authentication middleware for Filament panel.
 *
 * Extends Filament's default Authenticate middleware to override the redirect behavior
 * for unauthenticated users, directing them to a custom login route instead of Filament's default.
 */
class FilamentAuthenticate extends Filament
{
    /**
     * Handle an unauthenticated user scenario.
     *
     * Throws an AuthenticationException when the user is not authenticated, providing a custom
     * redirect URL to the login page defined by the redirectTo method.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request instance.
     * @param  array<string>  $guards  The authentication guards being evaluated.
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException Thrown when authentication fails, triggering a redirect.
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request)
        );
    }

    /**
     * Determine the redirect path for unauthenticated users.
     *
     * Provides the URL to redirect unauthenticated users to when they attempt to access
     * a protected Filament panel route. Returns the named 'login' route.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request instance.
     * @return string|null The URL to redirect to, or null if no redirect is applicable.
     */
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
