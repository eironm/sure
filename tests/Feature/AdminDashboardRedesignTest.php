<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardRedesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_with_cohesive_stat_cards_and_analytics(): void
    {
        $admin = User::factory()->create([
            'name'  => 'Super Admin',
            'role'  => 'admin',
        ]);

        $facility = Facility::factory()->create([
            'name' => 'University Main Auditorium',
        ]);

        // Create reservations across statuses
        Reservation::create([
            'user_id'           => $admin->id,
            'facility_id'       => $facility->id,
            'event_title'       => 'Annual Tech Summit',
            'reason'            => 'Annual technology conference and keynote presentations',
            'status'            => 'pending',
            'start_date'        => now()->addDays(1)->toDateString(),
            'end_date'          => now()->addDays(2)->toDateString(),
            'start_time'        => '09:00',
            'end_time'          => '17:00',
            'booking_reference' => 'DASH-001',
            'number_of_people'  => 120,
            'payment_status'    => 'pending',
            'payment_method'    => 'cash',
            'total_price'       => 25000,
            'amount_paid'       => 0,
        ]);

        Reservation::create([
            'user_id'           => $admin->id,
            'facility_id'       => $facility->id,
            'event_title'       => 'Graduation Rehearsal',
            'reason'            => 'Rehearsal for university graduation ceremony',
            'status'            => 'approved',
            'start_date'        => now()->addDays(3)->toDateString(),
            'end_date'          => now()->addDays(3)->toDateString(),
            'start_time'        => '13:00',
            'end_time'          => '16:00',
            'booking_reference' => 'DASH-002',
            'number_of_people'  => 300,
            'payment_status'    => 'paid',
            'payment_method'    => 'online',
            'total_price'       => 15000,
            'amount_paid'       => 15000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Header & Alert
        $response->assertSee('Action Required: Pending Approvals', false);
        $response->assertSee('Review Requests', false);

        // Cohesive Stat Cards
        $response->assertSee('Pending Approvals', false);
        $response->assertSee('Scheduled Bookings', false);
        $response->assertSee('Active Facilities', false);
        $response->assertSee('Collected This Month', false);

        // Chart.js Canvases
        $response->assertSee('bookingDemandChart', false);
        $response->assertSee('bookingStatusChart', false);
        $response->assertSee('chart.umd.min.js', false);

        // Recent Reservations Table
        $response->assertSee('Recent Reservations', false);
        $response->assertSee('Annual Tech Summit', false);
        $response->assertSee('Graduation Rehearsal', false);
        $response->assertSee('University Main Auditorium', false);

        // Mini Calendar Widget
        $response->assertSee('dash-mini-cal', false);
        $response->assertSee('has-event', false);

        // Quick Shortcuts & Top Venues
        $response->assertSee('Quick Shortcuts', false);
        $response->assertSee('Add Venue', false);
        $response->assertSee('Top Venues', false);
    }

    public function test_staff_role_can_view_dashboard_with_staff_navigation(): void
    {
        $staff = User::factory()->create([
            'name'  => 'Staff Member',
            'role'  => 'staff',
        ]);

        $response = $this->actingAs($staff)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard', false);
    }
}
