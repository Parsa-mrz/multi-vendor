<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

use function redirect;

/**
 * Handles the response when a user logs out.
 *
 * This class is responsible for clearing the user's token, logging out from Filament's authentication,
 * invalidating the session, and redirecting the user to the homepage.
 */
class LogoutResponse implements LogoutResponseContract
{

    /**
     * Handle the logout response.
     *
     * Clears the user's token, logs them out from Filament's authentication system,
     * invalidates the session, and regenerates the CSRF token.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request.
     * @return \Illuminate\Http\RedirectResponse The redirect response to the homepage.
     */
    public function toResponse($request): RedirectResponse
    {
        Filament::auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
