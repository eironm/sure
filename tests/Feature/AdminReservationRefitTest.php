<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReservationRefitTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_reservations_page_renders_with_long_unbroken_text(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $facility = Facility::factory()->create([
            'name' => 'University Main Auditorium',
        ]);

        $longText = str_repeat('A', 80);

        Reservation::create([
            'user_id'           => $admin->id,
            'facility_id'       => $facility->id,
            'event_title'       => $longText,
            'reason'            => $longText,
            'status'            => 'cancelled',
            'start_date'        => now()->addDays(2)->toDateString(),
            'end_date'          => now()->addDays(3)->toDateString(),
            'start_time'        => '08:00',
            'end_time'          => '17:00',
            'booking_reference' => 'REFIT-001',
            'number_of_people'  => 50,
            'payment_method'    => 'cash',
            'total_price'       => 40000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reservations.index'));
        $response->assertStatus(200);

        // Verify offcanvas container and layout elements
        $response->assertSee('reservation-offcanvas', false);
        $response->assertSee('oc-section', false);
        $response->assertSee('overflow-wrap: anywhere', false);
        $response->assertSee('overflow-x: hidden', false);
        $response->assertSee('Supporting Documents', false);
        $response->assertSee('Logistical Requirements', false);
    }

    public function test_calendar_events_api_returns_formatted_reservation(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $facility = Facility::factory()->create([
            'name' => 'University Main Auditorium',
        ]);

        $longText = str_repeat('A', 80);

        Reservation::create([
            'user_id'           => $admin->id,
            'facility_id'       => $facility->id,
            'event_title'       => $longText,
            'reason'            => $longText,
            'status'            => 'cancelled',
            'start_date'        => now()->addDays(2)->toDateString(),
            'end_date'          => now()->addDays(3)->toDateString(),
            'start_time'        => '08:00',
            'end_time'          => '17:00',
            'booking_reference' => 'REFIT-002',
            'number_of_people'  => 50,
            'payment_method'    => 'cash',
            'total_price'       => 40000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reservations.calendarEvents', [
            'start' => now()->startOfMonth()->toDateString(),
            'end'   => now()->endOfMonth()->toDateString(),
        ]));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertEquals($longText, $data[0]['extendedProps']['eventTitle']);
    }
}
