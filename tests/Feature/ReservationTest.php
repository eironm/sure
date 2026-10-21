<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_availability_logic_detects_conflicts()
    {
        // 1. Setup
        $user = User::factory()->create();
        $facility = Facility::factory()->create(['capacity' => 100]);
        
        // Create a slot for Monday (day 1)
        $slot = FacilityTimeSlot::create([
            'facility_id' => $facility->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '17:00',
            'capacity' => 1, // Only 1 slot available
            'is_active' => true
        ]);

        $monday = Carbon::parse('next monday');

        // 2. Initial check - should be available
        $check = ReservationAvailability::checkAvailability(
            $facility,
            $monday->toDateString(),
            $monday->toDateString(),
            '08:00',
            '17:00'
        );
        $this->assertTrue($check['available']);

        // 3. Create a reservation
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'start_date' => $monday->toDateString(),
            'end_date' => $monday->toDateString(),
            'start_time' => '08:00',
            'end_time' => '17:00',
            'status' => 'approved',
            'booking_reference' => 'TEST-123',
            'reason' => 'Test Meeting',
            'number_of_people' => 5,
        ]);

        // Manually link slot for test
        \App\Models\SlotReservation::create([
            'reservation_id' => $reservation->id,
            'facility_time_slot_id' => $slot->id,
            'reserved_date' => $monday->toDateString(),
            'quantity_booked' => 1
        ]);

        // 4. Check again - should NOT be available
        $checkAfter = ReservationAvailability::checkAvailability(
            $facility,
            $monday->toDateString(),
            $monday->toDateString(),
            '08:00',
            '17:00'
        );
        $this->assertFalse($checkAfter['available']);
        $this->assertEquals("Time slot 08:00-17:00 is full on " . $monday->format('M d'), $checkAfter['reason']);
    }

    public function test_slot_capacity_allows_concurrent_bookings_until_full()
    {
        $user = User::factory()->create();
        $facility = Facility::factory()->create(['capacity' => 100]);

        // Slot for Monday with capacity 2: two concurrent bookings allowed
        $slot = FacilityTimeSlot::create([
            'facility_id' => $facility->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '17:00',
            'capacity' => 2,
            'is_active' => true
        ]);

        $monday = Carbon::parse('next monday');

        $makeBooking = function (string $reference) use ($user, $facility, $slot, $monday) {
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'facility_id' => $facility->id,
                'start_date' => $monday->toDateString(),
                'end_date' => $monday->toDateString(),
                'start_time' => '08:00',
                'end_time' => '17:00',
                'status' => 'approved',
                'booking_reference' => $reference,
                'reason' => 'Test Meeting',
                'number_of_people' => 5,
            ]);

            \App\Models\SlotReservation::create([
                'reservation_id' => $reservation->id,
                'facility_time_slot_id' => $slot->id,
                'reserved_date' => $monday->toDateString(),
                'quantity_booked' => 1
            ]);
        };

        // First booking consumes 1 of 2 — still available
        $makeBooking('TEST-CAP-1');
        $check = ReservationAvailability::checkAvailability(
            $facility, $monday->toDateString(), $monday->toDateString(), '08:00', '17:00'
        );
        $this->assertTrue($check['available'], 'Capacity 2 should allow a second booking.');

        // Second booking fills the slot — now full
        $makeBooking('TEST-CAP-2');
        $checkFull = ReservationAvailability::checkAvailability(
            $facility, $monday->toDateString(), $monday->toDateString(), '08:00', '17:00'
        );
        $this->assertFalse($checkFull['available']);
        $this->assertEquals("Time slot 08:00-17:00 is full on " . $monday->format('M d'), $checkFull['reason']);
    }
}
