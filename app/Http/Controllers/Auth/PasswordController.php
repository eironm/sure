<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use App\Rules\NotPreviousPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * Enforces:
     *  1. Current password verification
     *  2. Password strength rules (via Password::defaults() — includes HIBP)
     *  3. Password history — new password must not match any previously used password
     *
     * After a successful change, the OLD password hash is stored in password_histories
     * so it can never be reused again.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => [
                'required',
                Password::defaults(),
                'confirmed',
                new NotPreviousPassword($user->id),
            ],
        ]);

        // ── Store the OLD (current) password hash before overwriting ──
        // On the very first password change, also record the original registration
        // password so it can't be cycled back to.
        try {
            $currentHash = $user->password;

            // Only store if this exact hash isn't already recorded
            $alreadyStored = PasswordHistory::where('user_id', $user->id)
                ->where('password_hash', $currentHash)
                ->exists();

            if (!$alreadyStored) {
                PasswordHistory::create([
                    'user_id'       => $user->id,
                    'password_hash' => $currentHash,
                ]);
            }

            // ── Update to the new password ──
            $user->update([
                'password' => $validated['password'],
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Password change failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            // Never let a failed password change go unnoticed — the user
            // MUST see an explicit error so they don't assume it worked.
            return back()->withInput($request->only('current_password', 'password', 'password_confirmation'))
                ->withErrors(['current_password' => 'The password could not be changed. Something went wrong on our side — please try again, and contact support if it keeps failing.'], 'updatePassword');
        }

        return back()->with('status', 'password-updated');
    }
}
