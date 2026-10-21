<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationWizardEndUserAutofillTest extends TestCase
{
    use RefreshDatabase;

    public function test_internal_user_with_college_autofills_college_and_name(): void
    {
        $internalUser = User::factory()->create([
            'role' => 'guest',
            'client_type' => 'internal',
            'college_department' => 'College of Engineering',
        ]);

        $response = $this->actingAs($internalUser)->get(route('guest.reserve'));

        $response->assertOk();
        // College radio checked
        $response->assertSee('value="college" required checked', false);
        // Name input value populated with college name
        $response->assertSee('value="College of Engineering"', false);
    }

    public function test_external_user_with_organization_autofills_organization_and_name(): void
    {
        $externalOrgUser = User::factory()->create([
            'role' => 'guest',
            'client_type' => 'external',
            'organization_name' => 'Innovate Tech Corp',
        ]);

        $response = $this->actingAs($externalOrgUser)->get(route('guest.reserve'));

        $response->assertOk();
        // Organization radio checked
        $response->assertSee('value="organization" required checked', false);
        // Name input value populated with organization name
        $response->assertSee('value="Innovate Tech Corp"', false);
    }

    public function test_external_user_without_organization_does_not_autofill(): void
    {
        $externalIndividual = User::factory()->create([
            'role' => 'guest',
            'client_type' => 'external',
            'organization_name' => null,
        ]);

        $response = $this->actingAs($externalIndividual)->get(route('guest.reserve'));

        $response->assertOk();
        // Neither college nor organization should be checked
        $response->assertDontSee('value="college" required checked', false);
        $response->assertDontSee('value="organization" required checked', false);
        // Name input should have empty value
        $response->assertSee('name="end_user_name" id="end_user_name" class="rw-input" placeholder="e.g. College of Engineering" value=""', false);
    }

    public function test_user_can_submit_with_custom_end_user_selection(): void
    {
        $internalUser = User::factory()->create([
            'role' => 'guest',
            'client_type' => 'internal',
            'college_department' => 'College of Engineering',
        ]);

        $facility = Facility::factory()->create(['capacity' => 100]);
        for ($day = 0; $day <= 6; $day++) {
            FacilityTimeSlot::create([
                'facility_id' => $facility->id,
                'day_of_week' => $day,
                'start_time' => '08:00',
                'end_time' => '18:00',
                'capacity' => 10,
                'is_active' => true,
            ]);
        }

        $startDate = now()->addDays(7)->toDateString();

        // User chooses to change from autofilled College to an Organization
        $response = $this->actingAs($internalUser)->post(route('guest.reserve.store'), [
            'event_title' => 'Inter-University Hackathon',
            'facility_id' => $facility->id,
            'start_date' => $startDate,
            'end_date' => $startDate,
            'start_time' => '09:00',
            'end_time' => '12:00',
            'number_of_people' => 30,
            'reason' => 'Annual student hackathon competition',
            'reserved_by_name' => $internalUser->name,
            'reserved_by_email' => $internalUser->email,
            'reserved_by_phone' => '+639171234567',
            'end_user_type' => 'organization',
            'end_user_name' => 'Robotics & AI Guild',
            'agreement_accepted' => '1',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'user_id' => $internalUser->id,
            'end_user_type' => 'organization',
            'end_user_name' => 'Robotics & AI Guild',
        ]);
    }
}
