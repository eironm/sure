<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_manage_admin_accounts_in_user_list()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin', 'is_active' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.users.index'));

        $response->assertOk();
        // Edit link for the admin account must be present
        $response->assertSee(route('superadmin.users.edit', $admin), false);
        // Toggle form for the admin account must be present
        $response->assertSee(route('superadmin.users.toggleActive', $admin), false);
    }

    public function test_superadmin_cannot_manage_other_superadmins_in_user_list()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin', 'is_active' => true]);
        $otherSuper = User::factory()->create(['role' => 'superadmin', 'is_active' => true]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.users.index'));

        $response->assertOk();
        $response->assertDontSee(route('superadmin.users.edit', $otherSuper), false);
        $response->assertSee('Locked');
    }

    public function test_superadmin_can_open_admin_edit_form()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin', 'is_active' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($superadmin)
            ->get(route('superadmin.users.edit', $admin))
            ->assertOk();
    }

    public function test_admin_can_open_guest_edit_form_and_see_destroy_route()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $guest = User::factory()->create(['role' => 'guest', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.edit', $guest));

        $response->assertOk();
        $response->assertSee(route('admin.users.destroy', $guest), false);
    }

    public function test_admin_can_destroy_manageable_user()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $guest = User::factory()->create(['role' => 'guest', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $guest));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $guest->id]);
    }

    public function test_admin_updating_user_updates_record_and_does_not_delete()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $guest = User::factory()->create([
            'role' => 'guest',
            'first_name' => 'Original',
            'last_name' => 'Name',
            'email' => 'original@example.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $guest), [
                'first_name' => 'Updated',
                'last_name' => 'Name',
                'email' => 'updated@example.com',
                'role' => 'guest',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $guest->id,
            'first_name' => 'Updated',
            'email' => 'updated@example.com',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'original@example.com',
        ]);
    }

    public function test_admin_edit_view_has_independent_forms()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $guest = User::factory()->create(['role' => 'guest', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.edit', $guest));

        $response->assertOk();
        $content = $response->getContent();

        // Ensure acctForm is closed before deleteUserForm begins
        $acctFormClosePos = strpos($content, '</form>');
        $deleteFormOpenPos = strpos($content, 'id="deleteUserForm"');

        $this->assertNotFalse($acctFormClosePos);
        $this->assertNotFalse($deleteFormOpenPos);
        $this->assertTrue(
            $acctFormClosePos < $deleteFormOpenPos,
            'The acctForm must be closed before the deleteUserForm to prevent nested forms from deleting the user on save.'
        );
    }
}

