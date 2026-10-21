<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_external_users_can_register_and_are_activated(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'External',
            'email' => 'external@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_agreed' => 'on',
            'client_type' => 'external',
            'contact_number' => '09171234567',
            'date_of_birth' => '1990-01-01',
            'sex' => 'Male',
            'civil_status' => 'Single',
            'nationality' => 'Filipino',
            'personal_address' => '123 Billing St, Manila',
            'billing_address' => '123 Billing St, Manila',
            'government_id_type' => 'Passport',
            'government_id_number' => 'N1234567A',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('verify-code'));
        
        $user = \App\Models\User::where('email', 'external@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->is_active);
        $this->assertEquals('external', $user->client_type);
        $this->assertEquals('09171234567', $user->contact_number);
    }

    public function test_new_internal_users_can_register_but_require_approval(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Internal',
            'email' => 'internal@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_agreed' => 'on',
            'client_type' => 'internal',
            'contact_number' => '09177654321',
            'date_of_birth' => '1990-01-01',
            'sex' => 'Male',
            'civil_status' => 'Single',
            'nationality' => 'Filipino',
            'personal_address' => '123 Academic Rd, Manila',
            'university_email' => 'john.doe@university.edu',
            'university_id' => '2023-00001-TU',
            'affiliation_type' => 'Student',
            'college_department' => 'College of Engineering',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('verify-code'));

        $user = \App\Models\User::where('email', 'internal@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->is_active);
        $this->assertEquals('internal', $user->client_type);
        $this->assertEquals('john.doe@university.edu', $user->university_email);
    }

    public function test_internal_user_requires_university_email(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Internal',
            'email' => 'internal2@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_agreed' => 'on',
            'client_type' => 'internal',
            'contact_number' => '09177654321',
            'date_of_birth' => '1990-01-01',
            'sex' => 'Male',
            'civil_status' => 'Single',
            'nationality' => 'Filipino',
            'personal_address' => '123 Academic Rd, Manila',
            // university_email intentionally omitted to test required rule
            'university_id' => '2023-00001-TU',
            'affiliation_type' => 'Student',
            'college_department' => 'College of Engineering',
        ]);

        $response->assertSessionHasErrors(['university_email']);
        $this->assertGuest();
    }

    public function test_external_user_must_be_of_legal_age(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Underage',
            'email' => 'underage@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_agreed' => 'on',
            'client_type' => 'external',
            'contact_number' => '09171234567',
            'date_of_birth' => now()->subYears(17)->format('Y-m-d'), // Under 18
            'sex' => 'Male',
            'civil_status' => 'Single',
            'nationality' => 'Filipino',
            'personal_address' => '123 Billing St, Manila',
            'billing_address' => '123 Billing St, Manila',
            'government_id_type' => 'Passport',
            'government_id_number' => 'N1234567A',
        ]);

        $response->assertSessionHasErrors(['date_of_birth']);
        $this->assertGuest();
    }
}
