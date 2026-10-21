<?php

namespace Tests\Feature\Auth;

use App\Mail\AccountApproved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Protects against the "Method Not Allowed" bug on toggle-active buttons:
 * a server-side BadMethodCallException (RedirectResponse::when() does not
 * exist) made toggle requests return 500; the SPA layer then fell back to a
 * full GET on the action URL, which produced the visible "Method Not Allowed"
 * page even though the toggle itself had already executed.
 */
class ToggleActiveNoServerErrorTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    private function superadminUser(): User
    {
        return User::factory()->create([
            'role' => User::ROLE_SUPERADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_toggle_active_succeeds_and_sends_mail(): void
    {
        Mail::fake();
        $admin = $this->adminUser();
        $target = User::factory()->create(['role' => User::ROLE_GUEST, 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.toggleActive', $target));

        $response->assertRedirect();
        $this->assertFalse($target->fresh()->is_active);
        Mail::assertSent(AccountApproved::class, 0);
        $this->assertDatabaseCount('audit_logs', 1);
    }

    public function test_superadmin_toggle_active_succeeds_with_mail_failure(): void
    {
        // Force the mail transport to throw so safe_mail returns false and the
        // controller takes the warning-flash branch that previously crashed.
        config(['mail.default' => 'smtp']);
        config(['mail.mailers.smtp.host' => '127.0.0.1']);
        config(['mail.mailers.smtp.port' => 1]);

        $super = $this->superadminUser();
        $target = User::factory()->create(['role' => User::ROLE_GUEST, 'is_active' => false]);

        $response = $this->actingAs($super)
            ->post(route('superadmin.users.toggleActive', $target));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('warning');
        $this->assertTrue($target->fresh()->is_active);
    }

    public function test_toggle_active_get_is_rejected(): void
    {
        $admin = $this->adminUser();
        $target = User::factory()->create(['role' => User::ROLE_GUEST]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.toggleActive', $target));

        $response->assertStatus(405);
        $this->assertNull($target->fresh()->is_active ? null : $target->fresh()->is_active);
    }
}
