<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Skip problematic migration in testing if necessary, 
        // but RefreshDatabase should handle it if the schema is valid.
    }

    public function test_admin_receives_notification_when_reservation_is_submitted()
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'guest']);
        $facility = Facility::factory()->create();

        $tomorrow = now()->addDays(7);
        \App\Models\FacilityTimeSlot::create([
            'facility_id' => $facility->id,
            'day_of_week' => $tomorrow->dayOfWeek,
            'start_time' => '08:00',
            'end_time' => '17:00',
            'capacity' => 1,
            'is_active' => true
        ]);

        $this->actingAs($user)->post(route('guest.reserve.store'), [
            'event_title' => 'Test Meeting Event',
            'facility_id' => $facility->id,
            'start_date' => $tomorrow->format('Y-m-d'),
            'end_date' => $tomorrow->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'reason' => 'Test Meeting',
            'number_of_people' => 5,
            'reserved_by_name' => $user->name,
            'reserved_by_email' => $user->email,
            'reserved_by_phone' => '+639171234567',
            'end_user_type' => 'organization',
            'end_user_name' => 'Test Club',
            'agreement_accepted' => '1',
            'payment_method' => 'cash',
        ]);

        Notification::assertSentTo($admin, ReservationStatusChanged::class, function ($notification) {
            return $notification->status === 'submitted';
        });
    }

    public function test_user_receives_notification_when_reservation_is_approved()
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'guest']);
        $facility = Facility::factory()->create();
        
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'reason' => 'Test Meeting',
            'number_of_people' => 5,
            'status' => 'pending',
            'booking_reference' => 'TEST1234',
            'total_price' => 100
        ]);

        $this->actingAs($admin)->post(route('admin.reservations.approve', $reservation->id), [
            'admin_remarks' => 'Approved!',
        ]);

        Notification::assertSentTo($user, ReservationStatusChanged::class, function ($notification) {
            return $notification->status === 'approved';
        });
    }
}
