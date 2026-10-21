<?php

namespace Tests\Feature;

use App\Mail\ContactInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->postJson(route('contact.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function test_contact_form_sends_email_and_returns_success(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, I would like to inquire about reserving a facility.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        Mail::assertSent(ContactInquiry::class, function ($mail) {
            return $mail->visitorName === 'John Doe'
                && $mail->visitorEmail === 'john@example.com'
                && str_contains($mail->body, 'Hello, I would like to inquire');
        });
    }
}
