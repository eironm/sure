<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

/**
 * Pre-login verification code flow.
 *
 *   GET  /verify-code           → enter-code screen
 *   POST /verify-code           → validate the 6-digit code
 *   POST /verification-code/resend → issue a fresh code
 *
 * Rules implemented:
 *   - External users: the code is sent automatically at registration.
 *     Entering a valid code activates the account (email verification
 *     happens alongside) and logs the user in.
 *   - Internal users: the code is sent at registration too, but the
 *     account additionally requires administrator approval (is_active).
 *     After entering a valid code they land on an "awaiting approval"
 *     screen until an admin activates their account.
 */
class VerificationCodeController extends Controller
{
    public const CODE_TTL_MINUTES = 10;
    public const RESEND_LIMIT = 3;          // resend attempts per 10 minutes
    public const RESEND_LIMIT_WINDOW = 600; // seconds
    public const ATTEMPT_LIMIT = 5;         // code-entry attempts per 10 minutes

    /**
     * Show the verification-code screen.
     */
    public function show(Request $request): View|RedirectResponse
    {
        // Already authenticated and fully verified → home
        if (Auth::check()) {
            if (Auth::user()->codeVerified()) {
                return redirect()->route('dashboard');
            }

            // Logged-in internal user awaiting approval — same dedicated
            // screen, so the messaging is identical everywhere.
            if (!Auth::user()->is_active) {
                return $this->approvalPending($request);
            }
        }

        // Primary: session set by registration / login. Fallback: ?email= query
        // parameter so a user who backed out of the code screen (or whose
        // session was cleared) can still reach it directly by email.
        $email = session('verify_email') ?: $request->query('email');
        if (!$email) {
            return redirect()->route('login')->with('info', 'Please sign in with your email first, then enter the code sent to you.');
        }

        $user = User::where('email', $email)->first();

        return view('auth.verify-code', compact('user', 'email'));
    }

    /**
     * Validate the entered verification code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $this->ensureIsNotAttemptLimited($request);

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'code'  => ['required', 'string', 'size:6', 'digits:6'],
        ], [
            'code.size'   => 'Please enter the full 6-digit code.',
            'code.digits' => 'The code must contain only digits.',
        ]);

        // Remember who is verifying so the code screen is never lost if the
        // session is otherwise cleared.
        $request->session()->put('verify_email', strtolower($request->email));

        $user = User::where('email', $request->email)->first();

        if (!$user || !$this->codeMatches($user, $request->code)) {
            RateLimiter::hit($this->attemptKey($request));

            $attemptsLeft = max(0, self::ATTEMPT_LIMIT - RateLimiter::attempts($this->attemptKey($request)));

            return back()->withInput()
                ->with('verify_error', 'That code is incorrect or has expired. '
                    . ($attemptsLeft > 0
                        ? "You have {$attemptsLeft} attempt" . ($attemptsLeft === 1 ? '' : 's') . ' left before a short cooldown.'
                        : 'Please wait a few minutes before trying again.'));
        }

        RateLimiter::clear($this->attemptKey($request));

        // Invalidate the used code
        $user->markEmailAsVerified();
        $user->update([
            'verification_code'          => null,
            'verification_code_expires_at' => null,
        ]);

        if (!$user->codeVerified()) {
            Log::warning('VerificationCodeController::verify — user not marked verified after code success', [
                'user_id' => $user->id,
            ]);

            return back()->with('verify_error', 'Something went wrong verifying your code. Please try again or request a new code.');
        }

        // External users: fully activate on code entry
        if ($user->client_type === 'external') {
            Auth::login($user, $request->boolean('remember'));

            return redirect()->route('dashboard')
                ->with('success', 'Your account is now active! Welcome to ' . config('app.name', 'SURE') . '.');
        }

        // Internal users: code is done, but admin approval is still required.
        // Show a dedicated "awaiting approval" screen so it is unambiguous
        // that the email is verified and only staff approval is left.
        return redirect()->route('awaiting-approval')->with('success',
            'Your email is verified! However, internal accounts must be approved by our staff before you can access the system. We\'ll notify you as soon as you can log in.');
    }

    /**
     * Show the "email verified, awaiting staff approval" screen for
     * internal accounts whose code has already been accepted.
     */
    public function approvalPending(Request $request): View|RedirectResponse
    {
        // Prefer the user attached to the session; fall back to the email
        // remembered from registration / code entry so the page is never
        // an empty state.
        $user = Auth::check() ? Auth::user() : null;

        $email = session('verify_email') ?: $request->query('email');
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            return redirect()->route('login')->with('info', 'Please sign in first.');
        }

        // Once an admin activates the account there is nothing left to wait
        // for — send them straight to the dashboard.
        if ($user->codeVerified() && $user->is_active) {
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Your account has been approved! Welcome to ' . config('app.name', 'SURE') . '.');
        }

        return view('auth.awaiting-approval', compact('user'));
    }

    /**
     * Send a fresh verification code to the user's email.
     */
    public function resend(Request $request): RedirectResponse
    {
        $this->ensureIsNotResendLimited($request);

        $request->validate(['email' => ['required', 'string', 'email']]);

        // Remember who requested a resend so /verify-code stays reachable.
        $request->session()->put('verify_email', strtolower($request->email));

        $user = User::where('email', $request->email)->first();

        // Privacy: never reveal whether an email exists in the system
        if ($user && !$user->codeVerified()) {
            $this->sendCode($user);
            RateLimiter::hit($this->resendKey($request));
        } else {
            // Keep throttle fairness even for unknown emails
            RateLimiter::hit($this->resendKey($request));
        }

        return back()->with('resend_success',
            'A new verification code has been sent to ' . e($request->email) . ' if an account exists.');
    }

    /* ── Helpers ─────────────────────────────────────────────── */

    /**
     * Generate and persist a new 6-digit code, then e-mail it (never
     * letting a mail failure crash the flow — safe_mail logs & continues).
     */
    public static function sendCode(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->update([
            'verification_code'          => Hash::make($code),
            'verification_code_expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
        ]);

        if (!safe_mail($user->email, new VerificationCodeMail($user, $code, self::CODE_TTL_MINUTES))) {
            Log::warning('Verification code email could not be sent', ['user_id' => $user->id, 'email' => $user->email]);
        }
    }

    /**
     * Timing-safe comparison of the entered code against the stored hash.
     */
    private function codeMatches(User $user, string $code): bool
    {
        if ($user->codeVerified() || empty($user->verification_code) || !$user->verification_code_expires_at) {
            return false;
        }

        if (now()->gt($user->verification_code_expires_at)) {
            return false;
        }

        return Hash::check($code, $user->verification_code);
    }

    /**
     * Rate-limit code entry attempts per email + IP.
     */
    private function ensureIsNotAttemptLimited(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($this->attemptKey($request), self::ATTEMPT_LIMIT)) {
            $seconds = RateLimiter::availableIn($this->attemptKey($request));

            abort(429, "Too many incorrect attempts. Please try again in " . ceil($seconds / 60) . ' minute(s).');
        }
    }

    /**
     * Rate-limit resend attempts per email + IP.
     */
    private function ensureIsNotResendLimited(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($this->resendKey($request), self::RESEND_LIMIT)) {
            $seconds = RateLimiter::availableIn($this->resendKey($request));

            abort(429, 'Please wait ' . ceil($seconds / 60) . ' minute(s) before requesting another code.');
        }
    }

    private function attemptKey(Request $request): string
    {
        return 'verify-code-attempts|' . strtolower($request->input('email', '')) . '|' . $request->ip();
    }

    private function resendKey(Request $request): string
    {
        return 'verify-code-resend|' . strtolower($request->input('email', '')) . '|' . $request->ip();
    }
}
