<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = strtolower(trim((string) $request->input('email')));
        $password = (string) $request->input('password');

        try {
            // Do not use Auth::attempt() here. On Wasmer's PHP/WASI runtime,
            // the failed-attempt path can terminate before Laravel renders
            // its normal validation redirect. Keep this flow explicit.
            $user = User::where('email', $email)->first();
            $validPassword = $user !== null && Hash::check($password, (string) $user->password);

            if (! $validPassword) {
                return redirect()->route('login')
                    ->withInput(['email' => $email])
                    ->withErrors(['email' => trans('auth.failed')]);
            }

            Auth::login($user, $request->boolean('remember'));
        } catch (\Throwable $e) {
            Log::error('Authentication failed unexpectedly.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->withInput(['email' => $email])
                ->withErrors(['email' => trans('auth.failed')]);
        }

        // Pre-login gate: verification code must be completed before the
        // session is allowed to proceed. External users receive the code
        // automatically at registration; internal users additionally
        // require administrator approval (is_active) after the code.
        if ($user->needsActivation()) {
          
            if (!$user->codeVerified()) {
                // Not logged in for real — drop the credential session so
                // the middleware doesn't treat this as an authenticated visit
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $request->session()->put('verify_email', strtolower($user->email));

                // Resume flow: any old/stale code is thrown away and a
                // BRAND-NEW code is generated and emailed. This guarantees
                // the user never tries a code that expired while they were
                // logged out or mid-verification.
                VerificationCodeController::sendCode($user);

                return redirect()->route('verify-code')->with('resend_success',
                    'A new verification code has been sent to ' . e($user->email) . ' — any previous code is no longer valid.');
            }

            // Code is done but account awaits admin approval (internal)
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->put('verify_email', strtolower($user->email));

            return redirect()->route('awaiting-approval')
                ->with('info', 'Your credentials are correct, but your account is still awaiting staff approval. You will be notified by email as soon as you can log in.');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
