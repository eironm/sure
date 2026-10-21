<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // ── Global password policy ──────────────────────────────────
        // This applies everywhere Password::defaults() is used:
        // registration, password change, admin user creation, reset.
        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()       // at least one uppercase + one lowercase
                ->numbers()         // at least one digit
                ->symbols();        // at least one special character
    
    });

    }
}
