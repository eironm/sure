<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CashierMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->canAccessCashier()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'You do not have access to the Cashier Panel.');
    }
}
