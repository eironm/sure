<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use Illuminate\Database\Seeder;

class FacilityTimeSlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::all();

        if ($facilities->isEmpty()) {
            $this->command->warn('No facilities found. Please seed facilities first.');
            return;
        }

        foreach ($facilities as $facility) {
            // Create standard business hours: 8 AM to 6 PM, Monday to Sunday
            $this->createStandardBusinessHours($facility);
        }

        $this->command->info('Facility time slots seeded successfully!');
    }

    /**
     * Create standard business hours for a facility.
     */
    private function createStandardBusinessHours(Facility $facility): void
    {
        // Operating hours: 8 AM to 6 PM (10 hours)
        $startHour = 8;
        $endHour = 18;

        // Create 1-hour slots for each day of the week (0 = Sunday, 6 = Saturday)
        for ($dayOfWeek = 0; $dayOfWeek <= 6; $dayOfWeek++) {
            for ($hour = $startHour; $hour < $endHour; $hour++) {
                $startTime = sprintf('%02d:00:00', $hour);
                $endTime = sprintf('%02d:00:00', $hour + 1);

                FacilityTimeSlot::firstOrCreate(
                    [
                        'facility_id' => $facility->id,
                        'day_of_week' => $dayOfWeek,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ],
                    [
                        'capacity' => 1,
                        'price_per_hour' => $facility->price_per_hour,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Create some premium slots with higher capacity and pricing
        // Morning slots (8 AM - 12 PM): 2 capacity, 1.2x price
        for ($dayOfWeek = 1; $dayOfWeek <= 5; $dayOfWeek++) { // Monday to Friday
            for ($hour = 8; $hour < 12; $hour++) {
                $slot = FacilityTimeSlot::where('facility_id', $facility->id)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('start_time', sprintf('%02d:00:00', $hour))
                    ->first();

                if ($slot) {
                    $slot->update([
                        'capacity' => 2,
                        'price_per_hour' => $facility->price_per_hour * 1.2,
                    ]);
                }
            }
        }

        // Afternoon slots (2 PM - 6 PM): 1 capacity, standard price
        for ($dayOfWeek = 0; $dayOfWeek <= 6; $dayOfWeek++) {
            for ($hour = 14; $hour < 18; $hour++) {
                $slot = FacilityTimeSlot::where('facility_id', $facility->id)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('start_time', sprintf('%02d:00:00', $hour))
                    ->first();

                if ($slot) {
                    $slot->update([
                        'capacity' => 1,
                        'price_per_hour' => $facility->price_per_hour,
                    ]);
                }
            }
        }
    }
}
