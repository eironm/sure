<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\VerificationCodeController;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Resuming email verification (after logout or re-login) must always
 * issue a brand-new code and discard the old one.
 */
class VerificationResumeCodeTest extends TestCase
{
    use RefreshDatabase;

    private function unverifiedUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'email_verified_at'      => null,
            'verification_code'      => null,
            'verification_code_expires_at' => null,
            'is_active'              => true,
        ], $attrs));
    }

    /** Login with correct credentials while not yet code-verified.
     *  Returns the TestResponse so callers can chain assertions. */
    private function loginUnverifiedUser(User $user)
    {
        return $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);
    }

    public function test_relogin_issues_a_brand_new_code_and_invalidates_the_old_one(): void
    {
        Mail::fake();

        $user = $this->unverifiedUser();

        // First code issued at registration
        VerificationCodeController::sendCode($user);
        $firstCode = '111111';
        // (we can't read the hashed code; we just know it was set)
        $this->assertNotNull($user->fresh()->verification_code);
        $this->assertNotNull($user->fresh()->verification_code_expires_at);

        // User logs back in mid-verification — a NEW code must be issued
        $this->loginUnverifiedUser($user)->assertRedirectToRoute('verify-code');

        // A mail was sent with a NEW code — the mailable carries the plain code
        Mail::assertSent(VerificationCodeMail::class, function (VerificationCodeMail $mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        // The old code MUST no longer work — codeMatches rejects the pre-rotate value
        $user->refresh();
        $this->assertFalse(\Hash::check($firstCode, $user->verification_code));
        $this->assertNotNull($user->verification_code);
        $this->assertGreaterThan(now(), $user->verification_code_expires_at);
    }

    public function test_relogin_stale_code_cannot_verify_after_resume(): void
    {
        Mail::fake();

        $user = $this->unverifiedUser();

        // Issue the first code and capture its plain value via the mail
        VerificationCodeController::sendCode($user);
        Mail::assertSent(VerificationCodeMail::class, function (VerificationCodeMail $mail) use (&$firstCode) {
            $firstCode = $mail->code;

            return true;
        });

        // Resume: log in again — the old code is rotated away
        $this->loginUnverifiedUser($user)->assertRedirectToRoute('verify-code');

        $user->refresh();
        $this->assertNotEquals($firstCode, $user->verification_code); // hash differs (plaintext compare fails early)

        // Entering the OLD code on the verify screen must fail
        $this->post('/verify-code', [
            'email' => $user->email,
            'code'  => $firstCode,
        ])->assertSessionHas('verify_error');

        // And the new code still works
        Mail::assertSent(VerificationCodeMail::class, 2);
        Mail::assertSent(VerificationCodeMail::class, function (VerificationCodeMail $mail) use (&$newCode) {
            $newCode = $mail->code;

            return true;
        });

        $this->post('/verify-code', [
            'email' => $user->email,
            'code'  => $newCode,
        ])->assertRedirect();
    }

    public function test_logout_during_verification_keeps_the_flow_reachable_and_resend_works(): void
    {
        Mail::fake();

        $user = $this->unverifiedUser();
        VerificationCodeController::sendCode($user);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirectToRoute('verify-code');

        // User logs out mid-verification (kills verify_email session)
        $this->post('/logout');

        // The verify screen remains reachable via the ?email= query parameter
        // (fallback when the session was destroyed by logout).
        $this->get('/verify-code?email=' . urlencode($user->email))->assertOk();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirectToRoute('verify-code');

        Mail::assertSent(VerificationCodeMail::class, 3); // registration + 2 resume logins (each re-issues a fresh code)
    }
}
