<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilityTimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = DB::table('facilities')->get();

        if ($facilities->isEmpty()) {
            return;
        }

        $timeSlots = [];
        
        // Define standard business hours: 8 AM to 6 PM (10 slots of 1 hour each)
        $startHour = 8;
        $endHour = 18;

        foreach ($facilities as $facility) {
            // Create slots for each day of the week (0=Sunday to 6=Saturday)
            for ($day = 0; $day <= 6; $day++) {
                // Skip weekends for some facilities if needed, but for testing let's do all days
                for ($hour = $startHour; $hour < $endHour; $hour++) {
                    $startTime = sprintf('%02d:00:00', $hour);
                    $endTime = sprintf('%02d:00:00', $hour + 1);
                    
                    $timeSlots[] = [
                        'facility_id' => $facility->id,
                        'day_of_week' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'capacity' => $facility->capacity ?? 1,
                        'price_per_hour' => $facility->price_per_hour,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($timeSlots, 100) as $chunk) {
            DB::table('facility_time_slots')->insert($chunk);
        }
    }
}
