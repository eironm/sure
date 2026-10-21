<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternationalPhoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_international_phone_numbers(): void
    {
        $internationalNumbers = [
            '+12025550199', // US
            '+447911123456', // UK
            '+639171234567', // PH international
            '09171234567',   // PH local standard
            '+819012345678', // Japan
            '+6581234567',   // Singapore
        ];

        foreach ($internationalNumbers as $i => $phone) {
            $email = "intl_user_{$i}@example.com";
            $response = $this->post('/register', [
                'first_name' => 'Intl',
                'last_name' => 'User',
                'email' => $email,
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'terms_agreed' => 'on',
                'client_type' => 'external',
                'contact_number' => $phone,
                'date_of_birth' => '1992-05-10',
                'sex' => 'Female',
                'civil_status' => 'Single',
                'nationality' => 'Filipino',
                'personal_address' => 'Sample Address, Global City',
                'billing_address' => 'Sample Address, Global City',
                'government_id_type' => 'Passport',
                'government_id_number' => "P123456{$i}A",
            ]);

            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('users', [
                'email' => $email,
                'contact_number' => $phone,
            ]);
        }
    }

    public function test_registration_rejects_invalid_phone_formats(): void
    {
        $invalidNumbers = ['123', 'invalid-phone', '08123', '+1', '+99999999999999999999'];

        foreach ($invalidNumbers as $i => $invalidPhone) {
            $response = $this->post('/register', [
                'first_name' => 'Bad',
                'last_name' => "Phone{$i}",
                'email' => "bad_phone_{$i}@example.com",
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'terms_agreed' => 'on',
                'client_type' => 'external',
                'contact_number' => $invalidPhone,
                'date_of_birth' => '1995-01-01',
                'sex' => 'Male',
                'civil_status' => 'Single',
                'nationality' => 'Filipino',
                'personal_address' => 'Address',
                'billing_address' => 'Address',
                'government_id_type' => 'Passport',
                'government_id_number' => "P00000{$i}",
            ]);

            $response->assertSessionHasErrors(['contact_number']);
        }
    }

    public function test_profile_contact_can_be_updated_with_international_number(): void
    {
        $user = User::factory()->create([
            'role' => 'guest',
            'contact_number' => '09171234567',
        ]);

        $response = $this->actingAs($user)->postJson(route('profile.contact.update'), [
            'contact_number' => '+12025550199',
            'personal_address' => '742 Evergreen Terrace',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'contact_number' => '+12025550199',
        ]);

        $this->assertEquals('+12025550199', $user->fresh()->contact_number);
    }

    public function test_admin_can_create_and_update_user_with_international_contact_number(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create user with international number
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe_admin_create@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'staff',
            'contact_number' => '+447911123456',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $createdUser = User::where('email', 'johndoe_admin_create@example.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertEquals('+447911123456', $createdUser->contact_number);

        // Update user to different international number
        $updateResponse = $this->actingAs($admin)->put(route('admin.users.update', $createdUser), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe_admin_create@example.com',
            'role' => 'staff',
            'contact_number' => '+61412345678',
        ]);

        $updateResponse->assertRedirect(route('admin.users.index'));
        $this->assertEquals('+61412345678', $createdUser->fresh()->contact_number);

        // Update user with empty optional contact number
        $emptyResponse = $this->actingAs($admin)->put(route('admin.users.update', $createdUser), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe_admin_create@example.com',
            'role' => 'staff',
            'contact_number' => '',
        ]);

        $emptyResponse->assertRedirect(route('admin.users.index'));
        $this->assertNull($createdUser->fresh()->contact_number);
    }
}
