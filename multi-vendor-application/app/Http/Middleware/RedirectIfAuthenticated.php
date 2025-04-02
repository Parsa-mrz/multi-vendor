<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to redirect authenticated users away from restricted routes.
 *
 * This middleware checks if a user is authenticated using Laravel's authentication system.
 * If authenticated, it redirects the user to the dashboard to prevent access to pages like login.
 * If not authenticated, it allows the request to proceed to the next middleware or route handler.
 */
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming HTTP request.
     *
     * Determines whether the user is authenticated. If so, redirects to the dashboard route.
     * Otherwise, passes the request to the next middleware in the stack.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request instance.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next  The next middleware or route handler to execute.
     * @return \Symfony\Component\HttpFoundation\Response The HTTP response, either a redirect or the result of the next handler.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
