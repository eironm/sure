<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\CashierMiddleware;
use App\Http\Middleware\CheckPaymentDeadlines;
use App\Http\Middleware\EnsureAccountIsActive;
use App\Http\Middleware\SecurityHeaders;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'           => AdminMiddleware::class,
            'superadmin'      => SuperAdminMiddleware::class,
            'cashier.panel'   => CashierMiddleware::class,
            'check.deadlines' => CheckPaymentDeadlines::class,
            'account.active'  => EnsureAccountIsActive::class,
        ]);

        // Run on every web request
        $middleware->appendToGroup('web', CheckPaymentDeadlines::class);
        $middleware->appendToGroup('web', EnsureAccountIsActive::class);

        // Security hardening headers on every response
        $middleware->appendToGroup('web', SecurityHeaders::class);

        $middleware->validateCsrfTokens(except: [
            'api/check-availability',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // In production, never leak stack traces, file paths, or framework
        // internals to users. Render everything else via our custom error views.
        $exceptions->shouldRenderJsonWhen(function () {
            return request()->is('api/*') || request()->wantsJson();
        });

        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'message' => 'The uploaded file is too large for the server. Maximum allowed request size is ' . ini_get('post_max_size') . '.',
                ], 413);
            }

            return back()->with('error', 'The uploaded file exceeds the server limit (' . ini_get('post_max_size') . '). Please choose a smaller file.');
        });

        $exceptions->render(function (\Throwable $e) {
            // Anything that bubbles up to a 500 is logged with a reference ID
            // and rendered by resources/views/errors/500.blade.php (no trace).
            if (app()->environment('production') && !request()->wantsJson()) {
                report($e);
                return response()->view('errors.500', [
                    'exception' => $e,
                ], 500);
            }
        });
    })->create();

