<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        return redirect()->route('guest.dashboard')->with('error', 'Access restricted to Super Administrators.');
    }
}
