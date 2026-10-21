<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use App\Models\Reservation;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationWhitelistSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_reserve_is_blocked_when_user_has_active_reservation_and_setting_is_disabled()
    {
        SystemSetting::setValue('allow_multiple_advance_reservations', '0');

        $user = User::factory()->create(['role' => 'guest']);
        $facility = Facility::factory()->create();

        Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'start_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '11:00',
            'reason' => 'Existing reservation',
            'number_of_people' => 20,
            'status' => 'pending',
            'booking_reference' => 'WL-EXISTING-001',
            'total_price' => 1000,
        ]);

        $this->actingAs($user)
            ->get(route('guest.reserve'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error', 'You already have a pending or active reservation.');
    }

    public function test_store_allows_multiple_advance_reservations_when_setting_is_enabled()
    {
        SystemSetting::setValue('allow_multiple_advance_reservations', '1');

        $user = User::factory()->create(['role' => 'guest']);
        $existingFacility = Facility::factory()->create();
        $newFacility = Facility::factory()->create();

        Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $existingFacility->id,
            'start_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '11:00',
            'reason' => 'First reservation',
            'number_of_people' => 20,
            'status' => 'pending',
            'booking_reference' => 'WL-EXISTING-002',
            'total_price' => 1000,
        ]);

        $date = Carbon::parse('next monday')->addWeek();

        FacilityTimeSlot::create([
            'facility_id' => $newFacility->id,
            'day_of_week' => $date->dayOfWeek,
            'start_time' => '08:00',
            'end_time' => '17:00',
            'capacity' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->from(route('guest.reserve'))->post(route('guest.reserve.store'), [
            'event_title' => 'Second Event',
            'start_date' => $date->toDateString(),
            'end_date' => $date->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'number_of_people' => 30,
            'reason' => 'Second reservation for same account',
            'facility_id' => $newFacility->id,
            'reserved_by_name' => 'Test User',
            'reserved_by_email' => $user->email,
            'reserved_by_phone' => '09123456789',
            'end_user_type' => 'organization',
            'end_user_name' => 'Test Organization',
            'payment_method' => 'cash',
            'agreement_accepted' => '1',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseCount('reservations', 2);
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'facility_id' => $newFacility->id,
            'event_title' => 'Second Event',
        ]);
    }
}

