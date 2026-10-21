<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalAgreementPersistenceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * When the legal checkbox is checked but the rest of the form is invalid,
     * the server must remember the agreement so the register page re-renders
     * with the checkbox checked and locked.
     */
    public function test_legal_agreement_persists_across_registration_validation_failure(): void
    {
        // Render the register page first so the POST carries a valid Referer
        // header (matches what the browser does) and redirect()->back() has a
        // sane target instead of falling back to the app root.
        $this->get('/register')->assertOk();

        $response = $this->post('/register', [
            'first_name'   => 'Jane',
            'middle_name'  => null,
            'last_name'    => 'Doe',
            'email'        => 'jane@example.com',
            'password'     => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'contact_number' => '09171234567',
            'client_type'  => 'external',
            'terms_agreed' => '1',
            // Deliberately invalid: under-age date of birth
            'date_of_birth' => '2020-01-01',
            'sex'          => 'Female',
            'civil_status' => 'Single',
            'nationality'  => 'Filipino',
            'personal_address' => '123 Main St',
            'organization_name' => 'Individual',
            'billing_address' => '123 Main St',
            'government_id_type' => 'Passport',
            'government_id_number' => 'P1234567A',
        ]);

        $response->assertRedirect(); // validation failure -> redirect back

        // The flag must still be in the session so the register page restores
        // the checked/locked checkbox instead of making the user re-read the
        // legal documents.
        $this->assertTrue(
            session('registration_legal_agreed', false) === true,
            'registration_legal_agreed flag must persist after a validation failure'
        );

        // And the re-rendered page must output a checked, disabled checkbox.
        $response->assertRedirect('/register');
        $this->get('/register')->assertSee('termsCheckbox', false);
    }
}
