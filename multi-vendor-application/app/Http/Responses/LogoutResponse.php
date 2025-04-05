<?php

namespace App\Http\Responses;

use App\Traits\TokenManagementTrait;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

use function redirect;

class LogoutResponse implements LogoutResponseContract
{
    use TokenManagementTrait;

    public function toResponse($request): RedirectResponse
    {
        $this->clearUserToken();
        Filament::auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
