<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use App\Models\LogisticalItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationWizardLogisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_logistics_step_renders_sidebar_viewport_warning_modal_and_booking_summary(): void
    {
        $user = User::factory()->create(['role' => 'guest', 'client_type' => 'external']);

        // Create logistical items
        LogisticalItem::create([
            'name' => 'Monoblock Chairs',
            'category' => 'Furniture',
            'max_quantity' => 200,
            'price' => 0.00,
            'unit_label' => 'pc',
            'is_active' => true,
        ]);

        LogisticalItem::create([
            'name' => 'Wireless Microphone',
            'category' => 'Audio/Visual',
            'max_quantity' => 10,
            'price' => 150.00,
            'unit_label' => 'set',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('guest.reserve'));

        $response->assertStatus(200);

        // Category sidebar elements
        $response->assertSee('rw-logi-sidebar', false);
        $response->assertSee('rw-logi-viewport', false);
        $response->assertSee('rwLogiViewport', false);
        $response->assertSee('rw-logi-nav', false);
        $response->assertSee('rw-logi-nav-item', false);

        // Your Booking summary card logistics elements
        $response->assertSee('id="q-logi-row"', false);
        $response->assertSee('id="q-logi-badge"', false);
        $response->assertSee('id="q-logi-summary-text"', false);
        $response->assertSee('id="q-logi-items-list"', false);

        // Zero-selection warning modal
        $response->assertSee('id="logiZeroModal"', false);
        $response->assertSee('No Arrangements Selected', false);
        $response->assertSee('Included for Free', false);
        $response->assertSee('confirmProceedWithoutLogi', false);
    }

    public function test_guest_can_submit_reservation_with_arrangements(): void
    {
        $user = User::factory()->create(['role' => 'guest', 'client_type' => 'external']);
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

        $item = LogisticalItem::create([
            'name' => 'Long Tables',
            'category' => 'Furniture',
            'max_quantity' => 30,
            'price' => 50.00,
            'unit_label' => 'pc',
            'is_active' => true,
        ]);

        $startDate = now()->addDays(7)->toDateString();

        $response = $this->actingAs($user)->post(route('guest.reserve.store'), [
            'event_title' => 'Annual Conference',
            'facility_id' => $facility->id,
            'start_date' => $startDate,
            'end_date' => $startDate,
            'start_time' => '09:00',
            'end_time' => '12:00',
            'number_of_people' => 45,
            'reason' => 'Annual organizational gathering',
            'client_type' => 'external',
            'reserved_by_name' => 'Jane Organizer',
            'reserved_by_email' => 'jane@example.com',
            'reserved_by_phone' => '+639171234567',
            'end_user_type' => 'organization',
            'end_user_name' => 'Tech Summit Guild',
            'agreement_accepted' => '1',
            'payment_method' => 'cash',
            'arrangements' => json_encode([
                ['item_id' => $item->id, 'quantity' => 5]
            ]),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'event_title' => 'Annual Conference',
        ]);
    }
}
