<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Block deactivated accounts from accessing any authenticated route.
 * This runs as part of the web middleware group.
 */
class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            // Allow users to verify their email or log out even if account is not active yet
            if ($request->routeIs('verification.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            $user = Auth::user();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $errorMsg = ($user->client_type === 'internal')
                ? 'Your email is verified! However, internal accounts require administrator approval before you can log in.'
                : 'Your account has been deactivated. Please contact an administrator.';

            return redirect()->route('login')->with('error', $errorMsg);
        }

        return $next($request);
    }
}
