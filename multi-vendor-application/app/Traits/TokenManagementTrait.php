<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;

trait TokenManagementTrait
{
    /**
     * Set the user token in a cookie.
     *
     * @param string $token
     * @return void
     */
    public function setUserToken(string $token)
    {
        Cookie::queue(Cookie::make('token', $token, 1440));
    }

    /**
     * Get the user token from the cookie.
     *
     * @return string|null
     */
    public function getUserToken()
    {
        return request()->cookie('token');
    }

    /**
     * Remove the user token from the cookie.
     *
     * @return void
     */
    public function clearUserToken()
    {
        Cookie::queue(Cookie::forget('token'));
    }

    /**
     * Check if the token is valid.
     *
     * @return bool
     */
    public function isUserTokenValid()
    {
        $token = $this->getUserToken();

        if (!$token) {
            return false;
        }

        $personalAccessToken = PersonalAccessToken::findToken($token);

        if (!$personalAccessToken) {
            return false;
        }

        $user = $personalAccessToken->tokenable;

        return $user !== null;
    }

}
