<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\FacilityBlackoutDate;
use App\Models\FacilityCategory;
use App\Models\FacilityOperatingHour;
use App\Models\FacilityTag;
use App\Models\FacilityTimeSlot;
use App\Models\Reservation;
use App\Models\SlotReservation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * SampleDataSeeder
 *
 * Seeds a realistic, fully-interlocked dataset for manual testing.
 * Covers: sample accounts, categories, tags, facilities, operating hours,
 * time slots, slot reservations, reservations (pending/approved/denied/cancelled),
 * and blackout dates.
 *
 * Safe to re-run: uses updateOrCreate / firstOrCreate where possible,
 * and truncates only sample-owned reservation rows on each run.
 *
 * Run: php artisan db:seed --class=SampleDataSeeder
 * Reset: php artisan migrate:fresh --seed  (or just re-run this seeder)
 */
class SampleDataSeeder extends Seeder
{
    // ── Date Anchors ──────────────────────────────────────────────────────────
    // All dates are relative to TODAY so the data always feels "live".

    private \Carbon\Carbon $today;
    private \Carbon\Carbon $yesterday;
    private \Carbon\Carbon $nextWeek;
    private \Carbon\Carbon $twoWeeksOut;
    private \Carbon\Carbon $threeWeeksOut;
    private \Carbon\Carbon $lastWeek;
    private \Carbon\Carbon $twoWeeksAgo;
    private \Carbon\Carbon $oneMonthAgo;
    private \Carbon\Carbon $twoMonthsAgo;
    private \Carbon\Carbon $threeMonthsAgo;
    private \Carbon\Carbon $fourMonthsAgo;
    private \Carbon\Carbon $fiveMonthsAgo;
    private \Carbon\Carbon $sixMonthsAgo;

    public function run(): void
    {
        $this->today          = now()->startOfDay();
        $this->yesterday      = now()->subDay()->startOfDay();
        $this->lastWeek       = now()->subWeek()->startOfDay();
        $this->twoWeeksAgo    = now()->subWeeks(2)->startOfDay();
        $this->nextWeek       = now()->addWeek()->startOfDay();
        $this->twoWeeksOut    = now()->addWeeks(2)->startOfDay();
        $this->threeWeeksOut  = now()->addWeeks(3)->startOfDay();
        $this->oneMonthAgo    = now()->subMonth()->startOfDay();
        $this->twoMonthsAgo   = now()->subMonths(2)->startOfDay();
        $this->threeMonthsAgo = now()->subMonths(3)->startOfDay();
        $this->fourMonthsAgo  = now()->subMonths(4)->startOfDay();
        $this->fiveMonthsAgo  = now()->subMonths(5)->startOfDay();
        $this->sixMonthsAgo   = now()->subMonths(6)->startOfDay();

        $this->command->info('🌱  SampleDataSeeder starting...');

        $users      = $this->seedSampleUsers();
        $categories = $this->seedCategories();
        $tags       = $this->seedTags();
        $facilities = $this->seedFacilities($categories, $tags);
        $this->seedOperatingHours($facilities);
        $slots      = $this->seedTimeSlots($facilities);
        $this->seedBlackoutDates($facilities);
        $this->seedReservations($users, $facilities, $slots);
        $this->seedHistoricalReservations($users, $facilities, $slots);
        $this->seedCashierPendingPayments($users, $facilities, $slots);

        $this->command->info('✅  SampleDataSeeder complete! Happy testing.');
        $this->command->table(
            ['Account', 'Email', 'Password', 'Role'],
            collect($this->accountTable())->map(fn($r) => array_values($r))->toArray()
        );
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 1. SAMPLE USER ACCOUNTS
    // ═══════════════════════════════════════════════════════════════════════

    private function seedSampleUsers(): array
    {
        $this->command->info('  → Seeding sample user accounts…');

        $accounts = [
            // Internal guests (students / faculty)
            [
                'name'              => 'Alice Santos',
                'first_name'        => 'Alice',
                'last_name'         => 'Santos',
                'email'             => 'alice.santos@sample.test',
                'password'          => 'password123',
                'role'              => User::ROLE_GUEST,
                'client_type'       => 'internal',
                'contact_number'    => '09171234001',
                'affiliation_type'  => 'Student',
                'college_department'=> 'College of Computer Studies',
                'university_id'     => '2021-00001',
                'university_email'  => 'alice.santos@university.edu',
                'sex'               => 'Female',
                'civil_status'      => 'Single',
                'nationality'       => 'Filipino',
            ],
            [
                'name'              => 'Bob Reyes',
                'first_name'        => 'Bob',
                'last_name'         => 'Reyes',
                'email'             => 'bob.reyes@sample.test',
                'password'          => 'password123',
                'role'              => User::ROLE_GUEST,
                'client_type'       => 'internal',
                'contact_number'    => '09171234002',
                'affiliation_type'  => 'Faculty',
                'college_department'=> 'College of Engineering',
                'university_id'     => '2015-FA-042',
                'university_email'  => 'bob.reyes@university.edu',
                'sex'               => 'Male',
                'civil_status'      => 'Married',
                'nationality'       => 'Filipino',
            ],
            [
                'name'              => 'Clara Lim',
                'first_name'        => 'Clara',
                'last_name'         => 'Lim',
                'email'             => 'clara.lim@sample.test',
                'password'          => 'password123',
                'role'              => User::ROLE_GUEST,
                'client_type'       => 'internal',
                'contact_number'    => '09171234003',
                'affiliation_type'  => 'Admin/Staff',
                'college_department'=> 'Office of Student Affairs',
                'university_id'     => '2018-AS-007',
                'university_email'  => 'clara.lim@university.edu',
                'sex'               => 'Female',
                'civil_status'      => 'Single',
                'nationality'       => 'Filipino',
            ],
            // External guests (organizations / companies)
            [
                'name'              => 'David Cruz',
                'first_name'        => 'David',
                'last_name'         => 'Cruz',
                'email'             => 'david.cruz@sample.test',
                'password'          => 'password123',
                'role'              => User::ROLE_GUEST,
                'client_type'       => 'external',
                'contact_number'    => '09181234001',
                'organization_name' => 'Cruz Events Management',
                'date_of_birth'     => '1990-06-15',
                'billing_address'   => '123 Bonifacio St., Makati City',
                'government_id_type'=> "Driver's License",
                'government_id_number' => 'DL-2020-12345',
                'sex'               => 'Male',
                'civil_status'      => 'Married',
                'nationality'       => 'Filipino',
            ],
            [
                'name'              => 'Eva Tan',
                'first_name'        => 'Eva',
                'last_name'         => 'Tan',
                'email'             => 'eva.tan@sample.test',
                'password'          => 'password123',
                'role'              => User::ROLE_GUEST,
                'client_type'       => 'external',
                'contact_number'    => '09181234002',
                'organization_name' => 'Pinoy Tech Summit',
                'date_of_birth'     => '1985-03-22',
                'billing_address'   => '456 Roxas Blvd., Pasay City',
                'government_id_type'=> 'Passport',
                'government_id_number' => 'P-A123456B',
                'sex'               => 'Female',
                'civil_status'      => 'Single',
                'nationality'       => 'Filipino',
            ],
        ];

        $created = [];
        foreach ($accounts as $data) {
            $password = $data['password'];
            unset($data['password']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'          => $password,
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ])
            );
            $created[$data['email']] = $user;
        }

        return $created;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 2. FACILITY CATEGORIES
    // ═══════════════════════════════════════════════════════════════════════

    private function seedCategories(): array
    {
        $this->command->info('  → Seeding facility categories…');

        $cats = [
            ['name' => 'Auditorium',    'description' => 'Large-scale event and performance venues.',        'color' => '#6366f1', 'icon' => 'fa-microphone-alt'],
            ['name' => 'Sports',        'description' => 'Athletic courts, fields, and gymnasiums.',         'color' => '#22c55e', 'icon' => 'fa-running'],
            ['name' => 'Meeting Rooms', 'description' => 'Small to mid-sized rooms for meetings/workshops.', 'color' => '#f59e0b', 'icon' => 'fa-handshake'],
            ['name' => 'Outdoor',       'description' => 'Open-air grounds, courts, and fields.',            'color' => '#10b981', 'icon' => 'fa-tree'],
            ['name' => 'Laboratory',    'description' => 'Specialized labs for research and education.',     'color' => '#3b82f6', 'icon' => 'fa-flask'],
        ];

        $created = [];
        foreach ($cats as $cat) {
            $obj = FacilityCategory::updateOrCreate(['name' => $cat['name']], $cat);
            $created[$cat['name']] = $obj;
        }

        return $created;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 3. FACILITY TAGS
    // ═══════════════════════════════════════════════════════════════════════

    private function seedTags(): array
    {
        $this->command->info('  → Seeding facility tags…');

        $tags = [
            ['name' => 'Air-Conditioned',      'description' => 'Venue has air conditioning.',            'color' => '#60a5fa'],
            ['name' => 'AV-Equipped',          'description' => 'Has built-in audio/visual equipment.',   'color' => '#a78bfa'],
            ['name' => 'Wheelchair Accessible','description' => 'PWD-accessible entrance and facilities.', 'color' => '#34d399'],
            ['name' => 'Outdoor',              'description' => 'Open-air venue.',                         'color' => '#86efac'],
            ['name' => 'WiFi Available',       'description' => 'Free WiFi inside the venue.',             'color' => '#fbbf24'],
            ['name' => 'Catering Allowed',     'description' => 'Outside catering is permitted.',          'color' => '#f97316'],
            ['name' => 'Parking',              'description' => 'On-site parking available.',              'color' => '#94a3b8'],
            ['name' => 'Stage',                'description' => 'Has a dedicated stage or platform.',      'color' => '#e879f9'],
            ['name' => 'Pool / Shower',        'description' => 'Includes shower / pool facilities.',      'color' => '#38bdf8'],
            ['name' => 'No Smoking',           'description' => 'Strictly no smoking inside the venue.',   'color' => '#f87171'],
        ];

        $created = [];
        foreach ($tags as $tag) {
            $obj = FacilityTag::updateOrCreate(['name' => $tag['name']], $tag);
            $created[$tag['name']] = $obj;
        }

        return $created;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 4. FACILITIES
    // ═══════════════════════════════════════════════════════════════════════

    private function seedFacilities(array $categories, array $tags): array
    {
        $this->command->info('  → Seeding facilities…');

        $defs = [
            [
                'name'             => 'University Main Auditorium',
                'description'      => 'Our flagship 800-seat auditorium equipped with state-of-the-art sound and lighting systems. Ideal for graduations, concerts, lectures, and large-scale corporate events.',
                'location'         => 'Main Campus – Building A, Ground Floor',
                'capacity'         => 800,
                'price_per_hour'   => 5000.00,
                'is_available'     => true,
                'amenities'        => "800 theater-style seats\nProfessional PA system\nLED stage lighting\nBackstage dressing rooms\nHDMI / VGA projection\nAir conditioning throughout",
                'contact_person'   => 'Maria Santos',
                'contact_phone'    => '02-8888-0001',
                'terms_conditions' => "Venue must be returned in original condition.\nNo open flames or pyrotechnics allowed.\nExternal caterers must be pre-approved.\nSetup/teardown time is NOT included in the booking window.\nCancellations 7 days before event qualify for 50% refund.",
                'category'         => 'Auditorium',
                'tags'             => ['Air-Conditioned', 'AV-Equipped', 'Stage', 'Wheelchair Accessible', 'WiFi Available', 'Catering Allowed'],
            ],
            [
                'name'             => 'Multi-Purpose Gymnasium',
                'description'      => 'A full-size NCAA-regulation basketball court that converts into a badminton, volleyball, or futsal court. Hosts intramurals, corporate team-buildings, and community sports events.',
                'location'         => 'Sports Complex – North Wing',
                'capacity'         => 1200,
                'price_per_hour'   => 3500.00,
                'is_available'     => true,
                'amenities'        => "Regulation basketball court (hardwood)\n4 badminton courts (lines painted)\nScoreboard\nLocker rooms & showers\nBleacher seating for 1,200\nVentilation fans",
                'contact_person'   => 'Jose Reyes',
                'contact_phone'    => '02-8888-0002',
                'terms_conditions' => "Sports attire and rubber-soled shoes required.\nNo food or drinks on the court.\nEquipment damage is the renter's responsibility.\nNo cleats or spikes allowed on the hardwood floor.",
                'category'         => 'Sports',
                'tags'             => ['Wheelchair Accessible', 'Parking', 'Pool / Shower', 'No Smoking'],
            ],
            [
                'name'             => 'Innovation Hub – Conference Room A',
                'description'      => 'A sleek, 40-person executive conference room with a 90-inch interactive smart board and video-conferencing capabilities. Perfect for board meetings, workshops, and training sessions.',
                'location'         => 'Innovation Hub – 3rd Floor, Room 301',
                'capacity'         => 40,
                'price_per_hour'   => 1200.00,
                'is_available'     => true,
                'amenities'        => "90-inch interactive touchscreen\nZoom/Teams-ready video conferencing\nWhiteboard walls\nU-shape table seating\nHigh-speed WiFi\nCoffee/tea station",
                'contact_person'   => 'Luz Dela Cruz',
                'contact_phone'    => '02-8888-0003',
                'terms_conditions' => "Maximum 40 occupants at all times.\nNo rearranging of built-in furniture.\nEquipment must be returned to default settings after use.\nBookings must be made at least 48 hours in advance.",
                'category'         => 'Meeting Rooms',
                'tags'             => ['Air-Conditioned', 'AV-Equipped', 'WiFi Available', 'Wheelchair Accessible', 'No Smoking'],
            ],
            [
                'name'             => 'Open-Air Amphitheatre',
                'description'      => 'A 500-capacity open-air venue nestled among landscaped gardens. Ideal for outdoor concerts, cultural performances, graduation ceremonies, and evening galas.',
                'location'         => 'Centennial Park – East Side',
                'capacity'         => 500,
                'price_per_hour'   => 2500.00,
                'is_available'     => true,
                'amenities'        => "Curved tiered seating\nOutdoor PA system\nLED perimeter lighting\nBackstage green room\nParking for 200 vehicles\nLandscaped surroundings",
                'contact_person'   => 'Ramon Torres',
                'contact_phone'    => '02-8888-0004',
                'terms_conditions' => "No booking during typhoon signal #2 or higher.\nOutside generators must be pre-approved.\nVenue must be vacated by 10:00 PM.\nGarbage segregation strictly enforced.",
                'category'         => 'Outdoor',
                'tags'             => ['Outdoor', 'Stage', 'Catering Allowed', 'Parking', 'No Smoking'],
            ],
            [
                'name'             => 'Science & Research Laboratory 1',
                'description'      => 'A state-of-the-art research laboratory with 30 fully-equipped workstations, fume hoods, and a controlled-environment room. Available for academic research, industrial testing, and training.',
                'location'         => 'Science Building – 2nd Floor, Lab 201',
                'capacity'         => 30,
                'price_per_hour'   => 800.00,
                'is_available'     => true,
                'amenities'        => "30 workstations with gas outlets\n6 fume hoods\nControlled-temp room (4-25 deg C)\nEquipment sterilization room\nSafety shower & eyewash station\nLocked chemical storage",
                'contact_person'   => 'Dr. Ana Reyes',
                'contact_phone'    => '02-8888-0005',
                'terms_conditions' => "PPE (lab coat, goggles, gloves) mandatory at all times.\nHazardous chemicals must be declared prior to use.\nAll experiments must comply with university biosafety protocols.\nNo food or beverages inside the laboratory.",
                'category'         => 'Laboratory',
                'tags'             => ['Air-Conditioned', 'WiFi Available', 'No Smoking'],
            ],
            [
                'name'             => 'Rooftop Events Deck',
                'description'      => 'An exclusive 200-person rooftop event space with panoramic city views, ideal for cocktail receptions, product launches, and intimate evening gatherings.',
                'location'         => 'Main Building – Rooftop, 10th Floor',
                'capacity'         => 200,
                'price_per_hour'   => 4000.00,
                'is_available'     => false, // Currently under renovation — tests "unavailable" status
                'amenities'        => "City panorama views\nOutdoor lounge furniture\nPortable bar setup\nFairy light canopy\nPA-ready power outlets\nSmall kitchenette",
                'contact_person'   => 'Carlo Mendoza',
                'contact_phone'    => '02-8888-0006',
                'terms_conditions' => "Maximum 200 persons at all times (fire code).\nAlcoholic beverages must be licensed.\nVenue closes at 11:00 PM strict.\nHelicopter landings strictly prohibited.",
                'category'         => 'Outdoor',
                'tags'             => ['Outdoor', 'Catering Allowed', 'No Smoking'],
            ],
        ];

        $created = [];
        foreach ($defs as $def) {
            $catName  = $def['category'];
            $tagNames = $def['tags'];
            unset($def['category'], $def['tags']);

            $def['category_id'] = isset($categories[$catName]) ? $categories[$catName]->id : null;

            $facility = Facility::updateOrCreate(
                ['name' => $def['name']],
                $def
            );

            // Sync tags
            $tagIds = collect($tagNames)
                ->map(fn($t) => $tags[$t]->id ?? null)
                ->filter()
                ->values()
                ->toArray();
            $facility->tags()->sync($tagIds);

            $created[$facility->name] = $facility;
        }

        return $created;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 5. OPERATING HOURS
    // ═══════════════════════════════════════════════════════════════════════

    private function seedOperatingHours(array $facilities): void
    {
        $this->command->info('  → Seeding operating hours…');

        // day_of_week: 0=Sunday, 1=Monday, …, 6=Saturday
        $schedules = [
            'University Main Auditorium'              => $this->weekdaySchedule('08:00', '22:00', ['08:00', '20:00']),
            'Multi-Purpose Gymnasium'                 => $this->allDaySchedule('06:00', '21:00'),
            'Innovation Hub – Conference Room A'      => $this->weekdaySchedule('07:00', '21:00', null),
            'Open-Air Amphitheatre'                   => $this->weekdaySchedule('08:00', '22:00', ['08:00', '22:00']),
            'Science & Research Laboratory 1'         => $this->weekdaySchedule('07:00', '20:00', null),
            'Rooftop Events Deck'                     => $this->weekdaySchedule('14:00', '23:00', ['14:00', '23:00']),
        ];

        foreach ($facilities as $name => $facility) {
            $schedule = $schedules[$name] ?? $this->allDaySchedule('08:00', '17:00');
            foreach ($schedule as $day => $hours) {
                FacilityOperatingHour::updateOrCreate(
                    ['facility_id' => $facility->id, 'day_of_week' => $day],
                    [
                        'opening_time' => $hours['open'] ?? null,
                        'closing_time' => $hours['close'] ?? null,
                        'is_closed'    => $hours['closed'] ?? false,
                    ]
                );
            }
        }
    }

    /**
     * Build a per-day operating schedule.
     * Mon–Fri use $open/$close; weekends use $weekend [open, close] or null (closed).
     *
     * @param  array|null  $weekend  [openTime, closeTime] or null for closed
     */
    private function weekdaySchedule(string $open, string $close, ?array $weekend): array
    {
        $schedule = [];
        for ($d = 0; $d <= 6; $d++) {
            if ($d === 0 || $d === 6) {
                if ($weekend !== null) {
                    $schedule[$d] = ['open' => $weekend[0], 'close' => $weekend[1], 'closed' => false];
                } else {
                    $schedule[$d] = ['open' => null, 'close' => null, 'closed' => true];
                }
            } else {
                $schedule[$d] = ['open' => $open, 'close' => $close, 'closed' => false];
            }
        }
        return $schedule;
    }

    /** All 7 days open with same hours */
    private function allDaySchedule(string $open, string $close): array
    {
        $schedule = [];
        for ($d = 0; $d <= 6; $d++) {
            $schedule[$d] = ['open' => $open, 'close' => $close, 'closed' => false];
        }
        return $schedule;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 6. TIME SLOTS
    // ═══════════════════════════════════════════════════════════════════════

    private function seedTimeSlots(array $facilities): array
    {
        $this->command->info('  → Seeding facility time slots…');

        /**
         * Each entry: [start, end, capacity, price_per_hour, active, days]
         * days: array of day_of_week values (0=Sun … 6=Sat)
         */
        $slotDefs = [
            'University Main Auditorium' => [
                ['08:00', '12:00', 2, 4000.00, true,  [1,2,3,4,5]],
                ['08:00', '12:00', 1, 3500.00, true,  [0,6]],
                ['13:00', '17:00', 2, 4000.00, true,  [1,2,3,4,5]],
                ['13:00', '17:00', 1, 3500.00, true,  [0,6]],
                ['08:00', '17:00', 1, 20000.00, true, [1,2,3,4,5]],
                ['18:00', '22:00', 1, 5000.00, true,  [0,1,2,3,4,5,6]],
            ],
            'Multi-Purpose Gymnasium' => [
                ['06:00', '08:00', 3, 1500.00, true,  [0,1,2,3,4,5,6]],
                ['08:00', '10:00', 4, 2500.00, true,  [0,1,2,3,4,5,6]],
                ['10:00', '12:00', 4, 2500.00, true,  [0,1,2,3,4,5,6]],
                ['13:00', '15:00', 4, 2500.00, true,  [0,1,2,3,4,5,6]],
                ['15:00', '17:00', 4, 2500.00, true,  [0,1,2,3,4,5,6]],
                ['17:00', '19:00', 5, 3000.00, true,  [0,1,2,3,4,5,6]],
                ['19:00', '21:00', 5, 3000.00, true,  [0,1,2,3,4,5,6]],
            ],
            'Innovation Hub – Conference Room A' => [
                ['07:00', '09:00', 1,  900.00, true,  [1,2,3,4,5]],
                ['09:00', '12:00', 1, 1200.00, true,  [1,2,3,4,5]],
                ['13:00', '17:00', 1, 1200.00, true,  [1,2,3,4,5]],
                ['18:00', '21:00', 1, 1000.00, true,  [1,2,3,4,5]],
            ],
            'Open-Air Amphitheatre' => [
                ['08:00', '12:00', 1, 2000.00, true,  [0,1,2,3,4,5,6]],
                ['13:00', '18:00', 1, 2500.00, true,  [0,1,2,3,4,5,6]],
                ['18:00', '22:00', 1, 3500.00, true,  [0,1,2,3,4,5,6]],
                ['08:00', '22:00', 1, 18000.00, true, [0,6]],
            ],
            'Science & Research Laboratory 1' => [
                ['07:00', '09:00', 5, 600.00, true,  [1,2,3,4,5]],
                ['09:00', '12:00', 5, 800.00, true,  [1,2,3,4,5]],
                ['13:00', '17:00', 5, 800.00, true,  [1,2,3,4,5]],
                ['17:00', '20:00', 3, 700.00, true,  [1,2,3,4,5]],
            ],
            'Rooftop Events Deck' => [
                ['14:00', '17:00', 1, 3000.00, true,  [1,2,3,4,5]],
                ['18:00', '22:00', 1, 5000.00, true,  [0,1,2,3,4,5,6]],
                ['14:00', '23:00', 1, 22000.00, true, [0,6]],
                ['12:00', '14:00', 1, 2000.00, false, [1,2,3,4,5]], // Inactive – for testing
            ],
        ];

        $allSlots = []; // keyed by "FacilityName|dayOfWeek|HH:MM"

        foreach ($facilities as $name => $facility) {
            if (!isset($slotDefs[$name])) {
                continue;
            }

            foreach ($slotDefs[$name] as [$start, $end, $cap, $price, $active, $days]) {
                foreach ($days as $day) {
                    $slot = FacilityTimeSlot::updateOrCreate(
                        [
                            'facility_id' => $facility->id,
                            'day_of_week' => $day,
                            'start_time'  => $start . ':00',
                            'end_time'    => $end   . ':00',
                        ],
                        [
                            'capacity'       => $cap,
                            'price_per_hour' => $price,
                            'is_active'      => $active,
                        ]
                    );
                    $allSlots["{$name}|{$day}|{$start}"] = $slot;
                }
            }
        }

        return $allSlots;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 7. BLACKOUT DATES
    // ═══════════════════════════════════════════════════════════════════════

    private function seedBlackoutDates(array $facilities): void
    {
        $this->command->info('  → Seeding blackout dates…');

        // Remove existing sample-seeded blackout dates then re-insert cleanly
        FacilityBlackoutDate::whereIn(
            'facility_id',
            collect($facilities)->pluck('id')
        )->orWhere('reason', 'like', '%(SAMPLE)')->delete();

        $auditorium = $facilities['University Main Auditorium']     ?? null;
        $gym        = $facilities['Multi-Purpose Gymnasium']         ?? null;
        $lab        = $facilities['Science & Research Laboratory 1'] ?? null;

        $blackouts = [];

        // ── Global blackouts (facility_id = NULL means all facilities) ──────
        $blackouts[] = [
            'facility_id'       => null,
            'start_date'        => $this->today->copy()->addDays(10)->toDateString(),
            'end_date'          => $this->today->copy()->addDays(10)->toDateString(),
            'reason'            => 'University Foundation Day – All venues closed (SAMPLE)',
            'is_recurring'      => false,
            'recurring_pattern' => null,
        ];
        $blackouts[] = [
            'facility_id'       => null,
            'start_date'        => '2026-12-24',
            'end_date'          => '2026-12-26',
            'reason'            => 'Christmas Holidays – University closed (SAMPLE)',
            'is_recurring'      => true,
            'recurring_pattern' => 'annual',
        ];
        $blackouts[] = [
            'facility_id'       => null,
            'start_date'        => '2026-01-01',
            'end_date'          => '2026-01-01',
            'reason'            => "New Year's Day (SAMPLE)",
            'is_recurring'      => true,
            'recurring_pattern' => 'annual',
        ];

        // ── Facility-specific blackouts ──────────────────────────────────
        if ($auditorium) {
            $blackouts[] = [
                'facility_id'       => $auditorium->id,
                'start_date'        => $this->nextWeek->copy()->addDays(2)->toDateString(),
                'end_date'          => $this->nextWeek->copy()->addDays(3)->toDateString(),
                'reason'            => 'University Graduation Ceremony – pre-booked by Registrar (SAMPLE)',
                'is_recurring'      => false,
                'recurring_pattern' => null,
            ];
            $blackouts[] = [
                'facility_id'       => $auditorium->id,
                'start_date'        => $this->twoWeeksOut->copy()->addDays(1)->toDateString(),
                'end_date'          => $this->twoWeeksOut->copy()->addDays(1)->toDateString(),
                'reason'            => 'Annual Faculty Assembly (reserved) (SAMPLE)',
                'is_recurring'      => false,
                'recurring_pattern' => null,
            ];
        }

        if ($gym) {
            $blackouts[] = [
                'facility_id'       => $gym->id,
                'start_date'        => $this->nextWeek->copy()->addDays(4)->toDateString(),
                'end_date'          => $this->nextWeek->copy()->addDays(6)->toDateString(),
                'reason'            => 'Intramural Games – Floor resurfacing period (SAMPLE)',
                'is_recurring'      => false,
                'recurring_pattern' => null,
            ];
        }

        if ($lab) {
            $blackouts[] = [
                'facility_id'       => $lab->id,
                'start_date'        => $this->today->copy()->addDays(5)->toDateString(),
                'end_date'          => $this->today->copy()->addDays(6)->toDateString(),
                'reason'            => 'Scheduled equipment calibration and maintenance (SAMPLE)',
                'is_recurring'      => false,
                'recurring_pattern' => null,
            ];
        }

        $now = now();
        foreach ($blackouts as $b) {
            DB::table('facility_blackout_dates')->insert(array_merge($b, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 8. RESERVATIONS (pending, approved, denied, cancelled)
    // ═══════════════════════════════════════════════════════════════════════

    private function seedReservations(array $users, array $facilities, array $slots): void
    {
        $this->command->info('  → Seeding sample reservations…');

        // Clean up previous sample runs (all sample reservations use 'SAMPLE-' prefix)
        $oldIds = Reservation::where('booking_reference', 'like', 'SAMPLE-%')->pluck('id');
        if ($oldIds->isNotEmpty()) {
            SlotReservation::whereIn('reservation_id', $oldIds)->delete();
            Reservation::whereIn('id', $oldIds)->delete();
        }

        $alice   = $users['alice.santos@sample.test']  ?? null;
        $bob     = $users['bob.reyes@sample.test']      ?? null;
        $clara   = $users['clara.lim@sample.test']      ?? null;
        $david   = $users['david.cruz@sample.test']     ?? null;
        $eva     = $users['eva.tan@sample.test']        ?? null;
        $cashier = User::where('email', 'cashier@sure-system.com')->first();

        $auditorium   = $facilities['University Main Auditorium']             ?? null;
        $gym          = $facilities['Multi-Purpose Gymnasium']                 ?? null;
        $confRoom     = $facilities['Innovation Hub – Conference Room A']      ?? null;
        $amphitheatre = $facilities['Open-Air Amphitheatre']                   ?? null;
        $lab          = $facilities['Science & Research Laboratory 1']         ?? null;

        $count = 0;

        // ── 1. PENDING – Alice booking the auditorium next week (afternoon) ──
        if ($alice && $auditorium) {
            $date = $this->nextWeek->copy()->addDays(1);
            $dayOfWeek = $date->dayOfWeek;
            $slot = $this->findSlot($slots, 'University Main Auditorium', $dayOfWeek, '13:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $alice->id,
                    'facility_id'             => $auditorium->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '13:00:00',
                    'end_time'                => '17:00:00',
                    'reason'                  => 'CCS Department Seminar on Emerging Technologies in AI',
                    'event_title'             => 'AI & You: Emerging Tech Seminar',
                    'end_user_type'           => 'department',
                    'end_user_name'           => 'College of Computer Studies',
                    'reserved_by_name'        => 'Alice Santos',
                    'reserved_by_email'       => 'alice.santos@sample.test',
                    'reserved_by_phone'       => '09171234001',
                    'number_of_people'        => 250,
                    'status'                  => 'pending',
                    'client_type_snapshot'    => 'internal',
                    'endorsement_status'      => 'pending',
                    'endorsed_by_name'        => 'Dean Roberto Cruz',
                    'endorsed_by_position'    => 'Dean, College of Computer Studies',
                    'total_price'             => 16000.00,
                    'amount_paid'             => 0,
                    'payment_status'          => 'unpaid',
                    'security_deposit'        => 5000.00,
                    'security_deposit_status' => 'pending',
                    'is_fee_waived'           => false,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => now()->subHours(2),
                    'booking_reference'       => 'SAMPLE-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'     => json_encode([
                        ['label' => 'Auditorium (4 hrs × 1 day)', 'amount' => 16000],
                        ['label' => 'Security Deposit (refundable)', 'amount' => 5000],
                    ]),
                    'arrangements'            => json_encode([
                        ['item_id' => 1, 'name' => 'Monoblock Chairs',   'quantity' => 250, 'unit_price' => 0,    'subtotal' => 0],
                        ['item_id' => 6, 'name' => 'Projector + Screen', 'quantity' => 1,   'unit_price' => 1500, 'subtotal' => 1500],
                        ['item_id' => 7, 'name' => 'Wireless Microphone','quantity' => 2,   'unit_price' => 500,  'subtotal' => 1000],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'days'); // submitted a few days before
                $count++;
            }
        }

        // ── 2. APPROVED + PAID (cash) – Bob books the conference room ────
        if ($bob && $confRoom) {
            $date = $this->nextWeek->copy()->addDays(3);
            if ($date->isWeekend()) {
                $date = $date->nextWeekday();
            }
            $dayOfWeek = $date->dayOfWeek;
            $slot = $this->findSlot($slots, 'Innovation Hub – Conference Room A', $dayOfWeek, '09:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                   => $bob->id,
                    'facility_id'               => $confRoom->id,
                    'start_date'                => $date->toDateString(),
                    'end_date'                  => $date->toDateString(),
                    'start_time'                => '09:00:00',
                    'end_time'                  => '12:00:00',
                    'reason'                    => 'Engineering Department Research Proposal Defense',
                    'event_title'               => 'Research Defense – CpE Thesis Group 7',
                    'end_user_type'             => 'college',
                    'end_user_name'             => 'College of Engineering',
                    'reserved_by_name'          => 'Dr. Bob Reyes',
                    'reserved_by_email'         => 'bob.reyes@sample.test',
                    'reserved_by_phone'         => '09171234002',
                    'number_of_people'          => 20,
                    'status'                    => 'approved',
                    'admin_remarks'             => 'Approved. Endorse 48 hrs before event to confirm AV setup.',
                    'client_type_snapshot'      => 'internal',
                    'endorsement_status'        => 'endorsed',
                    'endorsed_by_name'          => 'Dean Maria Santos',
                    'endorsed_by_position'      => 'Dean, College of Engineering',
                    'total_price'               => 3600.00,
                    'amount_paid'               => 3600.00,
                    'payment_status'            => 'paid',
                    'payment_method'            => 'cash',
                    'security_deposit'          => 2000.00,
                    'security_deposit_paid'     => 2000.00,
                    'security_deposit_status'   => 'paid',
                    'is_fee_waived'             => false,
                    'agreement_accepted'        => true,
                    'agreement_accepted_at'     => now()->subDays(2),
                    'cashier_signed_by'         => $cashier?->id,
                    'cashier_signed_at'         => now()->subDay(),
                    'cash_receipt_number'       => 'OR-2026-00123',
                    'cash_payment_notes'        => 'Cash payment received in full. Official receipt issued.',
                    'official_receipt_number'   => 'OR-2026-00123',
                    'booking_reference'         => 'SAMPLE-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'       => json_encode([
                        ['label' => 'Conference Room A (3 hrs × 1 day)', 'amount' => 3600],
                        ['label' => 'Security Deposit (refundable)', 'amount' => 2000],
                    ]),
                    'arrangements'              => json_encode([
                        ['item_id' => 2, 'name' => 'Long Tables', 'quantity' => 5, 'unit_price' => 0, 'subtotal' => 0],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'weeks'); // booked 1–2 weeks ahead, already paid
                $count++;
            }
        }

        // ── 3. DENIED – Clara books the gym, denied due to conflict ──────
        if ($clara && $gym) {
            $date = $this->lastWeek->copy()->addDays(2);
            $dayOfWeek = $date->dayOfWeek;
            $slot = $this->findSlot($slots, 'Multi-Purpose Gymnasium', $dayOfWeek, '17:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $clara->id,
                    'facility_id'             => $gym->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '17:00:00',
                    'end_time'                => '19:00:00',
                    'reason'                  => 'OSA Student Government Volleyball Practice',
                    'event_title'             => 'SG Volleyball Team Practice',
                    'end_user_type'           => 'organization',
                    'end_user_name'           => 'Student Government Association',
                    'reserved_by_name'        => 'Clara Lim',
                    'reserved_by_email'       => 'clara.lim@sample.test',
                    'reserved_by_phone'       => '09171234003',
                    'number_of_people'        => 30,
                    'status'                  => 'denied',
                    'admin_remarks'           => 'Denied. Slot already reserved by Intramurals Committee for the opening ceremony rehearsal. Please select an alternative date or time.',
                    'client_type_snapshot'    => 'internal',
                    'endorsement_status'      => 'not_required',
                    'total_price'             => 6000.00,
                    'amount_paid'             => 0,
                    'payment_status'          => 'unpaid',
                    'security_deposit'        => 0,
                    'security_deposit_status' => 'not_required',
                    'is_fee_waived'           => true,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => $date->copy()->subDays(3),
                    'booking_reference'       => 'SAMPLE-' . strtoupper(Str::random(8)),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'weeks'); // submitted weeks before, denied after review
                $count++;
            }
        }

        // ── 4. CANCELLED – David booked amphitheatre, then cancelled ─────
        if ($david && $amphitheatre) {
            $date = $this->twoWeeksAgo->copy()->addDays(3);
            $dayOfWeek = $date->dayOfWeek;
            $slot = $this->findSlot($slots, 'Open-Air Amphitheatre', $dayOfWeek, '18:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                   => $david->id,
                    'facility_id'               => $amphitheatre->id,
                    'start_date'                => $date->toDateString(),
                    'end_date'                  => $date->toDateString(),
                    'start_time'                => '18:00:00',
                    'end_time'                  => '22:00:00',
                    'reason'                    => 'Product Launch – Cruz Events Management annual showcase',
                    'event_title'               => 'Cruz Events Showcase 2026',
                    'end_user_type'             => 'organization',
                    'end_user_name'             => 'Cruz Events Management',
                    'reserved_by_name'          => 'David Cruz',
                    'reserved_by_email'         => 'david.cruz@sample.test',
                    'reserved_by_phone'         => '09181234001',
                    'number_of_people'          => 300,
                    'status'                    => 'cancelled',
                    'admin_remarks'             => 'Approved prior to client-initiated cancellation.',
                    'client_type_snapshot'      => 'external',
                    'endorsement_status'        => 'not_required',
                    'total_price'               => 14000.00,
                    'amount_paid'               => 7000.00,
                    'payment_status'            => 'refunded',
                    'payment_method'            => 'gcash',
                    'security_deposit'          => 5000.00,
                    'security_deposit_paid'     => 5000.00,
                    'security_deposit_status'   => 'refunded',
                    'is_fee_waived'             => false,
                    'agreement_accepted'        => true,
                    'agreement_accepted_at'     => $date->copy()->subDays(7),
                    'cancellation_reason'       => 'Client changed venue due to weather concerns.',
                    'cancelled_at'              => $date->copy()->subDays(2),
                    'booking_reference'         => 'SAMPLE-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'       => json_encode([
                        ['label' => 'Amphitheatre (4 hrs × 1 day)', 'amount' => 14000],
                        ['label' => 'Security Deposit (refundable)', 'amount' => 5000],
                    ]),
                    'arrangements'              => json_encode([
                        ['item_id' => 4, 'name' => 'Sound System', 'quantity' => 1, 'unit_price' => 3000, 'subtotal' => 3000],
                        ['item_id' => 5, 'name' => 'LED Wall',     'quantity' => 1, 'unit_price' => 5000, 'subtotal' => 5000],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'months'); // planned months in advance, then cancelled
                $count++;
            }
        }

        // ── 5. APPROVED (multi-day, paid GCash) – Eva: Pinoy Tech Summit ─
        if ($eva && $auditorium) {
            $startDate = $this->twoWeeksOut->copy()->addDays(1);
            if ($startDate->isWeekend()) {
                $startDate = $startDate->nextWeekday();
            }
            $endDate = $startDate->copy()->addDays(2);

            $res = Reservation::create([
                'user_id'                   => $eva->id,
                'facility_id'               => $auditorium->id,
                'start_date'                => $startDate->toDateString(),
                'end_date'                  => $endDate->toDateString(),
                'start_time'                => '08:00:00',
                'end_time'                  => '12:00:00',
                'reason'                    => 'Pinoy Tech Summit 2026 – 3-day technology conference and expo',
                'event_title'               => 'Pinoy Tech Summit 2026',
                'end_user_type'             => 'organization',
                'end_user_name'             => 'Pinoy Tech Summit',
                'reserved_by_name'          => 'Eva Tan',
                'reserved_by_email'         => 'eva.tan@sample.test',
                'reserved_by_phone'         => '09181234002',
                'number_of_people'          => 700,
                'status'                    => 'approved',
                'admin_remarks'             => 'Approved. Full security deposit required before event. Coordinate with facilities team for booth layout.',
                'client_type_snapshot'      => 'external',
                'endorsement_status'        => 'not_required',
                'total_price'               => 48000.00,
                'amount_paid'               => 48000.00,
                'payment_status'            => 'paid',
                'payment_method'            => 'gcash',
                'security_deposit'          => 10000.00,
                'security_deposit_paid'     => 10000.00,
                'security_deposit_status'   => 'paid',
                'is_fee_waived'             => false,
                'agreement_accepted'        => true,
                'agreement_accepted_at'     => now()->subDays(5),
                'official_receipt_number'   => 'OR-2026-00456',
                'booking_reference'         => 'SAMPLE-' . strtoupper(Str::random(8)),
                'quotation_breakdown'       => json_encode([
                    ['label' => 'Auditorium (4 hrs × 3 days)', 'amount' => 48000],
                    ['label' => 'Sound System (3 days)', 'amount' => 9000],
                    ['label' => 'LED Wall (3 days)', 'amount' => 15000],
                    ['label' => 'Security Deposit (refundable)', 'amount' => 10000],
                ]),
                'arrangements'              => json_encode([
                    ['item_id' => 4, 'name' => 'Sound System',       'quantity' => 1, 'unit_price' => 3000, 'subtotal' => 9000],
                    ['item_id' => 5, 'name' => 'LED Wall',            'quantity' => 1, 'unit_price' => 5000, 'subtotal' => 15000],
                    ['item_id' => 7, 'name' => 'Wireless Microphone', 'quantity' => 4, 'unit_price' => 500,  'subtotal' => 6000],
                    ['item_id' => 9, 'name' => 'Stage / Platform',    'quantity' => 1, 'unit_price' => 2000, 'subtotal' => 2000],
                ]),
            ]);

            // Create slot_reservations for each of the 3 days
            for ($i = 0; $i <= 2; $i++) {
                $date = $startDate->copy()->addDays($i);
                $dayOfWeek = $date->dayOfWeek;
                $slot = $this->findSlot($slots, 'University Main Auditorium', $dayOfWeek, '08:00');
                if ($slot) {
                    SlotReservation::create([
                        'reservation_id'        => $res->id,
                        'facility_time_slot_id' => $slot->id,
                        'reserved_date'         => $date->toDateString(),
                        'quantity_booked'       => 1,
                        'price_snapshot'        => $slot->price_per_hour,
                    ]);
                }
            }
            $this->stampTimestamps($res, $startDate, 'months'); // big summit, booked months in advance
            $count++;
        }

        // ── 6. PENDING (fee-waived, endorsed) – Alice books the lab ──────
        if ($alice && $lab) {
            $date = $this->nextWeek->copy()->addDays(2);
            if ($date->isWeekend()) {
                $date = $date->nextWeekday();
            }
            $dayOfWeek = $date->dayOfWeek;
            $slot = $this->findSlot($slots, 'Science & Research Laboratory 1', $dayOfWeek, '13:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $alice->id,
                    'facility_id'             => $lab->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '13:00:00',
                    'end_time'                => '17:00:00',
                    'reason'                  => 'Undergraduate thesis research – Machine Learning model testing and data collection',
                    'event_title'             => 'ML Thesis Research Session',
                    'end_user_type'           => 'department',
                    'end_user_name'           => 'College of Computer Studies',
                    'reserved_by_name'        => 'Alice Santos',
                    'reserved_by_email'       => 'alice.santos@sample.test',
                    'reserved_by_phone'       => '09171234001',
                    'number_of_people'        => 5,
                    'status'                  => 'pending',
                    'client_type_snapshot'    => 'internal',
                    'endorsement_status'      => 'endorsed',
                    'endorsed_by_name'        => 'Prof. Juan dela Cruz',
                    'endorsed_by_position'    => 'Department Head, Information Technology',
                    'total_price'             => 0.00,
                    'amount_paid'             => 0,
                    'payment_status'          => 'unpaid',
                    'security_deposit'        => 0,
                    'security_deposit_status' => 'not_required',
                    'is_fee_waived'           => true,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => now()->subHours(5),
                    'booking_reference'       => 'SAMPLE-' . strtoupper(Str::random(8)),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => 0,
                ]);
                $this->stampTimestamps($res, $date, 'days'); // quick lab booking a day or two prior
                $count++;
            }
        }

        // ── 7. APPROVED (past/completed) – Bob booked gym last week ──────
        if ($bob && $gym) {
            $date = $this->lastWeek->copy()->addDays(1);
            $dayOfWeek = $date->dayOfWeek;
            $slot = $this->findSlot($slots, 'Multi-Purpose Gymnasium', $dayOfWeek, '08:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                   => $bob->id,
                    'facility_id'               => $gym->id,
                    'start_date'                => $date->toDateString(),
                    'end_date'                  => $date->toDateString(),
                    'start_time'                => '08:00:00',
                    'end_time'                  => '10:00:00',
                    'reason'                    => 'Annual Faculty Sports Day – Badminton tournament',
                    'event_title'               => 'Faculty Sports Day 2026',
                    'end_user_type'             => 'college',
                    'end_user_name'             => 'College of Engineering',
                    'reserved_by_name'          => 'Dr. Bob Reyes',
                    'reserved_by_email'         => 'bob.reyes@sample.test',
                    'reserved_by_phone'         => '09171234002',
                    'number_of_people'          => 50,
                    'status'                    => 'approved',
                    'admin_remarks'             => 'Approved. Enjoy the Faculty Sports Day!',
                    'client_type_snapshot'      => 'internal',
                    'endorsement_status'        => 'not_required',
                    'total_price'               => 5000.00,
                    'amount_paid'               => 5000.00,
                    'payment_status'            => 'paid',
                    'payment_method'            => 'cash',
                    'security_deposit'          => 0,
                    'security_deposit_status'   => 'not_required',
                    'is_fee_waived'             => false,
                    'agreement_accepted'        => true,
                    'agreement_accepted_at'     => $date->copy()->subDays(4),
                    'cashier_signed_by'         => $cashier?->id,
                    'cashier_signed_at'         => $date->copy()->subDays(1),
                    'cash_receipt_number'       => 'OR-2026-00087',
                    'official_receipt_number'   => 'OR-2026-00087',
                    'booking_reference'         => 'SAMPLE-' . strtoupper(Str::random(8)),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'weeks'); // booked a week or two before
                $count++;
            }
        }

        // ── 8. PENDING (future, external) – David books the gym ──────────
        if ($david && $gym) {
            $date = $this->threeWeeksOut->copy()->addDays(1);
            $dayOfWeek = $date->dayOfWeek;
            $slot = $this->findSlot($slots, 'Multi-Purpose Gymnasium', $dayOfWeek, '13:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $david->id,
                    'facility_id'             => $gym->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '13:00:00',
                    'end_time'                => '17:00:00',
                    'reason'                  => 'Corporate Team Building – Cruz Events Management quarterly bonding',
                    'event_title'             => 'CEM Team Building 2026',
                    'end_user_type'           => 'organization',
                    'end_user_name'           => 'Cruz Events Management',
                    'reserved_by_name'        => 'David Cruz',
                    'reserved_by_email'       => 'david.cruz@sample.test',
                    'reserved_by_phone'       => '09181234001',
                    'number_of_people'        => 80,
                    'status'                  => 'pending',
                    'client_type_snapshot'    => 'external',
                    'endorsement_status'      => 'not_required',
                    'total_price'             => 10000.00,
                    'amount_paid'             => 0,
                    'payment_status'          => 'unpaid',
                    'security_deposit'        => 3000.00,
                    'security_deposit_status' => 'pending',
                    'is_fee_waived'           => false,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => now()->subHours(1),
                    'booking_reference'       => 'SAMPLE-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'     => json_encode([
                        ['label' => 'Gymnasium (4 hrs × 1 day)', 'amount' => 10000],
                        ['label' => 'Security Deposit (refundable)', 'amount' => 3000],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'days'); // just submitted this week
                $count++;
            }
        }

        $this->command->info("  ✔  Created {$count} sample reservation(s).");
    }

    // ═══════════════════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Back-date a reservation's created_at / updated_at so reports and
     * analytics reflect realistic booking lead times.
     *
     * @param  Reservation        $res       The newly created reservation
     * @param  \Carbon\Carbon     $eventDate The actual event/start date
     * @param  string             $lead      'same_day'|'days'|'weeks'|'months'
     */
    private function stampTimestamps(Reservation $res, \Carbon\Carbon $eventDate, string $lead = 'weeks'): void
    {
        $submittedAt = match ($lead) {
            'same_day' => $eventDate->copy()->subHours(rand(2, 6)),
            'days'     => $eventDate->copy()->subDays(rand(2, 6))->setTime(rand(8, 16), rand(0, 59)),
            'weeks'    => $eventDate->copy()->subWeeks(rand(1, 3))->setTime(rand(8, 16), rand(0, 59)),
            'months'   => $eventDate->copy()->subMonths(rand(1, 2))->setTime(rand(8, 16), rand(0, 59)),
            default    => $eventDate->copy()->subWeeks(2),
        };

        // forceFill bypasses $fillable; then we save without re-touching timestamps
        $res->timestamps = false;
        $res->forceFill([
            'created_at' => $submittedAt,
            'updated_at' => $submittedAt,
        ])->save();
        $res->timestamps = true;
    }

    /**
     * Find a time slot from the in-memory slots array, falling back to a DB query.
     *
     * @param  array   $slots      All seeded slots keyed as "FacilityName|dayOfWeek|HH:MM"
     * @param  string  $facility   Facility name
     * @param  int     $dayOfWeek  0=Sunday … 6=Saturday
     * @param  string  $start      "HH:MM"
     */
    private function findSlot(array $slots, string $facility, int $dayOfWeek, string $start): ?FacilityTimeSlot
    {
        $key = "{$facility}|{$dayOfWeek}|{$start}";
        if (isset($slots[$key])) {
            return $slots[$key];
        }

        $fac = Facility::where('name', $facility)->first();
        if (!$fac) {
            return null;
        }

        return FacilityTimeSlot::where('facility_id', $fac->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', $start . ':00')
            ->first();
    }

    /** Credentials table printed at the end of the seed run */
    private function accountTable(): array
    {
        return [
            ['Account' => 'Alice Santos (Internal – Student)',    'Email' => 'alice.santos@sample.test', 'Password' => 'password123', 'Role' => 'guest'],
            ['Account' => 'Bob Reyes (Internal – Faculty)',       'Email' => 'bob.reyes@sample.test',    'Password' => 'password123', 'Role' => 'guest'],
            ['Account' => 'Clara Lim (Internal – Admin/Staff)',   'Email' => 'clara.lim@sample.test',    'Password' => 'password123', 'Role' => 'guest'],
            ['Account' => 'David Cruz (External – Company)',      'Email' => 'david.cruz@sample.test',   'Password' => 'password123', 'Role' => 'guest'],
            ['Account' => 'Eva Tan (External – NGO/Summit)',      'Email' => 'eva.tan@sample.test',      'Password' => 'password123', 'Role' => 'guest'],
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 9. HISTORICAL RESERVATIONS (for analytics, reports, booking demand)
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Seeds 6 months of historical approved/completed reservations so the
     * admin analytics dashboard, booking demand charts, and cashier reports
     * have real data to display.
     *
     * All records use the SAMPLE- prefix for safe cleanup on re-runs.
     */
    private function seedHistoricalReservations(array $users, array $facilities, array $slots): void
    {
        $this->command->info('  → Seeding historical reservations (6 months)…');

        // Clean up previous historical seed
        $oldIds = Reservation::where('booking_reference', 'like', 'HIST-%')->pluck('id');
        if ($oldIds->isNotEmpty()) {
            SlotReservation::whereIn('reservation_id', $oldIds)->delete();
            Reservation::whereIn('id', $oldIds)->delete();
        }

        $alice   = $users['alice.santos@sample.test']  ?? null;
        $bob     = $users['bob.reyes@sample.test']      ?? null;
        $clara   = $users['clara.lim@sample.test']      ?? null;
        $david   = $users['david.cruz@sample.test']     ?? null;
        $eva     = $users['eva.tan@sample.test']        ?? null;
        $cashier = User::where('email', 'cashier@sure-system.com')->first();

        $auditorium   = $facilities['University Main Auditorium']             ?? null;
        $gym          = $facilities['Multi-Purpose Gymnasium']                 ?? null;
        $confRoom     = $facilities['Innovation Hub – Conference Room A']      ?? null;
        $amphitheatre = $facilities['Open-Air Amphitheatre']                   ?? null;
        $lab          = $facilities['Science & Research Laboratory 1']         ?? null;

        $count = 0;

        /**
         * Each record: [user_key, facility, months_ago, day_offset, start_slot, status, amount, payment_method, event_title, people]
         */
        $histDefs = [
            // ── 6 months ago ─────────────────────────────────────────────────
            ['eva',   'auditorium',  6, 2, '08:00', 'approved', 40000.00, 'gcash',   'National Science Congress 2026',              600],
            ['david', 'gym',         6, 5, '08:00', 'approved', 7000.00,  'cash',    'Cruz Events: Staff Sportsfest',               150],

            // ── 5 months ago ─────────────────────────────────────────────────
            ['bob',   'confRoom',    5, 1, '09:00', 'approved', 3600.00,  'cash',    'Engineering Faculty Forum Q1',                 35],
            ['alice', 'lab',         5, 3, '13:00', 'approved', 0.00,     null,      'AI Research Data Collection Run 1',             5],
            ['eva',   'amphitheatre',5, 6, '18:00', 'approved', 14000.00, 'gcash',   'Pinoy Tech: Networking Night',               200],
            ['clara', 'gym',         5, 2, '17:00', 'denied',   0.00,     null,      'OSA Cheerdance Practice',                      40],

            // ── 4 months ago ─────────────────────────────────────────────────
            ['alice', 'auditorium',  4, 1, '13:00', 'approved', 16000.00, 'cash',    'CCS Week: Tech Talks Day 1',                 300],
            ['bob',   'gym',         4, 3, '08:00', 'approved', 5000.00,  'cash',    'Faculty Sports Day Q2',                       60],
            ['david', 'amphitheatre',4, 5, '13:00', 'cancelled', 0.00,    'gcash',   'Corporate Picnic (Cancelled - Rain)',          120],

            // ── 3 months ago ─────────────────────────────────────────────────
            ['eva',   'auditorium',  3, 2, '08:00', 'approved', 20000.00, 'gcash',   'Pinoy Tech Summit Dry Run',                  700],
            ['bob',   'confRoom',    3, 4, '13:00', 'approved', 3600.00,  'cash',    'Department Head Summit Q2',                   30],
            ['alice', 'lab',         3, 1, '09:00', 'approved', 0.00,     null,      'Thesis Data Collection – ML Phase 2',           5],
            ['clara', 'gym',         3, 3, '15:00', 'approved', 5000.00,  'cash',    'OSA Intramurals: Basketball Finals',          200],

            // ── 2 months ago ─────────────────────────────────────────────────
            ['david', 'auditorium',  2, 5, '18:00', 'approved', 20000.00, 'gcash',   'Cruz Events: Annual Gala Night',              700],
            ['eva',   'confRoom',    2, 2, '09:00', 'approved', 3600.00,  'gcash',   'Pinoy Tech: Board Planning Session',           15],
            ['bob',   'gym',         2, 1, '06:00', 'approved', 4500.00,  'cash',    'Early Morning Basketball Clinic',              60],
            ['alice', 'amphitheatre',2, 4, '13:00', 'approved', 10000.00, 'cash',    'CCS Week: Outdoor Demo Day',                 150],

            // ── 1 month ago ──────────────────────────────────────────────────
            ['eva',   'auditorium',  1, 3, '08:00', 'approved', 48000.00, 'gcash',   'Pinoy Tech Summit – Preview Edition',        700],
            ['david', 'gym',         1, 1, '13:00', 'approved', 10000.00, 'cash',    'CEM Team Building Q3',                        80],
            ['bob',   'confRoom',    1, 4, '07:00', 'approved', 2700.00,  'cash',    'Faculty Research Colloquium',                  20],
            ['alice', 'lab',         1, 2, '13:00', 'approved', 0.00,     null,      'Thesis Final Data Validation',                  5],
            ['clara', 'auditorium',  1, 5, '13:00', 'denied',   0.00,     null,      'OSA Recognition Night (Denied – Conflict)',   300],
        ];

        $userMap = [
            'alice' => $alice,
            'bob'   => $bob,
            'clara' => $clara,
            'david' => $david,
            'eva'   => $eva,
        ];
        $facilityMap = [
            'auditorium'   => $auditorium,
            'gym'          => $gym,
            'confRoom'     => $confRoom,
            'amphitheatre' => $amphitheatre,
            'lab'          => $lab,
        ];
        $clientTypeMap = [
            'alice' => 'internal',
            'bob'   => 'internal',
            'clara' => 'internal',
            'david' => 'external',
            'eva'   => 'external',
        ];
        $endUserMap = [
            'alice' => ['department', 'College of Computer Studies'],
            'bob'   => ['college', 'College of Engineering'],
            'clara' => ['organization', 'Student Government Association'],
            'david' => ['organization', 'Cruz Events Management'],
            'eva'   => ['organization', 'Pinoy Tech Summit'],
        ];

        foreach ($histDefs as $def) {
            [$userKey, $facKey, $monthsAgo, $dayOffset, $startSlot, $status, $amount, $payment, $eventTitle, $people] = $def;

            $user     = $userMap[$userKey]     ?? null;
            $facility = $facilityMap[$facKey]  ?? null;
            if (!$user || !$facility) continue;

            // Anchor date: N months ago + day offset (Mon–Fri preference)
            $monthAnchor = match($monthsAgo) {
                6 => $this->sixMonthsAgo,
                5 => $this->fiveMonthsAgo,
                4 => $this->fourMonthsAgo,
                3 => $this->threeMonthsAgo,
                2 => $this->twoMonthsAgo,
                1 => $this->oneMonthAgo,
                default => $this->oneMonthAgo,
            };
            $date = $monthAnchor->copy()->addDays($dayOffset);
            // Skew to a weekday for facilities that are weekday-only
            if ($facKey === 'confRoom' || $facKey === 'lab') {
                while ($date->isWeekend()) {
                    $date->addDay();
                }
            }
            $dayOfWeek = $date->dayOfWeek;

            $slot = $this->findSlot($slots, $facility->name, $dayOfWeek, $startSlot);
            // If no slot on that day, try the next available day
            if (!$slot) {
                for ($try = 1; $try <= 6; $try++) {
                    $tryDay = ($dayOfWeek + $try) % 7;
                    $slot = $this->findSlot($slots, $facility->name, $tryDay, $startSlot);
                    if ($slot) break;
                }
            }
            if (!$slot) continue;

            // Build payment/cashier info for completed approved records
            $isPaidApproved = $status === 'approved' && $amount > 0 && $payment !== null;
            $endUserInfo = $endUserMap[$userKey];
            $clientType  = $clientTypeMap[$userKey];

            $res = Reservation::create([
                'user_id'                 => $user->id,
                'facility_id'             => $facility->id,
                'start_date'              => $date->toDateString(),
                'end_date'                => $date->toDateString(),
                'start_time'              => $startSlot . ':00',
                'end_time'                => is_string($slot->end_time) ? $slot->end_time : $slot->end_time->format('H:i:s'),
                'reason'                  => $eventTitle,
                'event_title'             => $eventTitle,
                'end_user_type'           => $endUserInfo[0],
                'end_user_name'           => $endUserInfo[1],
                'reserved_by_name'        => $user->name,
                'reserved_by_email'       => $user->email,
                'reserved_by_phone'       => $user->contact_number,
                'number_of_people'        => $people,
                'status'                  => $status,
                'admin_remarks'           => $status === 'denied'
                    ? 'Denied: scheduling conflict with a pre-existing reservation.'
                    : ($status === 'cancelled' ? 'Cancelled by client due to weather.' : 'Approved and completed.'),
                'client_type_snapshot'    => $clientType,
                'endorsement_status'      => $status === 'approved' ? 'endorsed' : 'not_required',
                'total_price'             => $amount,
                'amount_paid'             => $isPaidApproved ? $amount : 0,
                'payment_status'          => $isPaidApproved ? 'paid' : ($status === 'denied' || $status === 'cancelled' ? 'unpaid' : 'unpaid'),
                'payment_method'          => $payment,
                'security_deposit'        => $amount > 0 ? round($amount * 0.1, 2) : 0,
                'security_deposit_paid'   => $isPaidApproved ? round($amount * 0.1, 2) : 0,
                'security_deposit_status' => $isPaidApproved ? 'refunded' : 'not_required',
                'is_fee_waived'           => $amount === 0.00 && $clientType === 'internal',
                'agreement_accepted'      => true,
                'agreement_accepted_at'   => $date->copy()->subDays(3),
                'cashier_signed_by'       => $isPaidApproved && $payment === 'cash' ? $cashier?->id : null,
                'cashier_signed_at'       => $isPaidApproved && $payment === 'cash' ? $date->copy()->subDay() : null,
                'cash_receipt_number'     => $isPaidApproved && $payment === 'cash'
                    ? 'OR-' . $date->format('Y') . '-' . str_pad(rand(1000, 9999), 5, '0', STR_PAD_LEFT)
                    : null,
                'official_receipt_number' => $isPaidApproved
                    ? 'OR-' . $date->format('Y') . '-' . str_pad(rand(1000, 9999), 5, '0', STR_PAD_LEFT)
                    : null,
                'booking_reference'       => 'HIST-' . strtoupper(Str::random(8)),
            ]);

            SlotReservation::create([
                'reservation_id'        => $res->id,
                'facility_time_slot_id' => $slot->id,
                'reserved_date'         => $date->toDateString(),
                'quantity_booked'       => 1,
                'price_snapshot'        => $slot->price_per_hour,
            ]);

            // Historical records: submitted weeks–months before the event
            $leadTime = ($monthsAgo >= 3) ? 'months' : 'weeks';
            $this->stampTimestamps($res, $date, $leadTime);
            $count++;
        }

        $this->command->info("  ✔  Created {$count} historical reservation(s) spanning 6 months.");
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 10. CASHIER PANEL – PENDING CASH PAYMENTS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Seeds reservations that are APPROVED but have cash payment still pending
     * (payment_status = 'cash_pending'). These are the records that appear in
     * the cashier panel waiting for the cashier to confirm receipt.
     *
     * Each record is fully tied to a real slot_reservation so the cashier can
     * see the full reservation details before confirming.
     */
    private function seedCashierPendingPayments(array $users, array $facilities, array $slots): void
    {
        $this->command->info('  → Seeding cashier panel pending cash payments…');

        // Clean up previous cashier seed
        $oldIds = Reservation::where('booking_reference', 'like', 'CASH-%')->pluck('id');
        if ($oldIds->isNotEmpty()) {
            SlotReservation::whereIn('reservation_id', $oldIds)->delete();
            Reservation::whereIn('id', $oldIds)->delete();
        }

        $alice   = $users['alice.santos@sample.test']  ?? null;
        $bob     = $users['bob.reyes@sample.test']      ?? null;
        $clara   = $users['clara.lim@sample.test']      ?? null;
        $david   = $users['david.cruz@sample.test']     ?? null;
        $eva     = $users['eva.tan@sample.test']        ?? null;

        $auditorium = $facilities['University Main Auditorium']             ?? null;
        $gym        = $facilities['Multi-Purpose Gymnasium']                 ?? null;
        $confRoom   = $facilities['Innovation Hub – Conference Room A']      ?? null;
        $lab        = $facilities['Science & Research Laboratory 1']         ?? null;

        $count = 0;

        // ── 1. Alice – Auditorium, approved + awaiting cash payment ──────────
        if ($alice && $auditorium) {
            $date = $this->nextWeek->copy()->addDays(4);
            while ($date->isWeekend()) { $date->addDay(); }
            $slot = $this->findSlot($slots, 'University Main Auditorium', $date->dayOfWeek, '13:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $alice->id,
                    'facility_id'             => $auditorium->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '13:00:00',
                    'end_time'                => '17:00:00',
                    'reason'                  => 'CCS Departmental Recognition Night – SY 2025-2026',
                    'event_title'             => 'CCS Recognition Night 2026',
                    'end_user_type'           => 'department',
                    'end_user_name'           => 'College of Computer Studies',
                    'reserved_by_name'        => 'Alice Santos',
                    'reserved_by_email'       => 'alice.santos@sample.test',
                    'reserved_by_phone'       => '09171234001',
                    'number_of_people'        => 350,
                    'status'                  => 'approved',
                    'admin_remarks'           => 'Approved. Client to pay cash at cashier window before event date.',
                    'client_type_snapshot'    => 'internal',
                    'endorsement_status'      => 'endorsed',
                    'endorsed_by_name'        => 'Dean Roberto Cruz',
                    'endorsed_by_position'    => 'Dean, College of Computer Studies',
                    'total_price'             => 16000.00,
                    'amount_paid'             => 0,
                    'payment_status'          => 'cash_pending',  // ← cashier panel picks this up
                    'payment_method'          => 'cash',
                    'payment_deadline'        => $date->copy()->subDay()->setTime(17, 0),
                    'security_deposit'        => 5000.00,
                    'security_deposit_paid'   => 0,
                    'security_deposit_status' => 'pending',
                    'is_fee_waived'           => false,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => now()->subDay(),
                    'booking_reference'       => 'CASH-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'     => json_encode([
                        ['label' => 'Auditorium (4 hrs × 1 day)', 'amount' => 16000],
                        ['label' => 'Projector + Screen (1 set)', 'amount' => 1500],
                        ['label' => 'Wireless Microphone (×2)',   'amount' => 1000],
                        ['label' => 'Security Deposit (refundable)', 'amount' => 5000],
                    ]),
                    'arrangements'            => json_encode([
                        ['item_id' => 1, 'name' => 'Monoblock Chairs',   'quantity' => 350, 'unit_price' => 0,    'subtotal' => 0],
                        ['item_id' => 6, 'name' => 'Projector + Screen', 'quantity' => 1,   'unit_price' => 1500, 'subtotal' => 1500],
                        ['item_id' => 7, 'name' => 'Wireless Microphone','quantity' => 2,   'unit_price' => 500,  'subtotal' => 1000],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'weeks'); // approved & awaiting cash, booked weeks ahead
                $count++;
            }
        }

        // ── 2. Bob – Conference Room, approved + cash pending ────────────────
        if ($bob && $confRoom) {
            $date = $this->twoWeeksOut->copy()->addDays(2);
            while ($date->isWeekend()) { $date->addDay(); }
            $slot = $this->findSlot($slots, 'Innovation Hub – Conference Room A', $date->dayOfWeek, '07:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $bob->id,
                    'facility_id'             => $confRoom->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '07:00:00',
                    'end_time'                => '09:00:00',
                    'reason'                  => 'Curriculum Committee Meeting – 2nd Semester Review',
                    'event_title'             => 'Curriculum Committee Q3 Review',
                    'end_user_type'           => 'college',
                    'end_user_name'           => 'College of Engineering',
                    'reserved_by_name'        => 'Dr. Bob Reyes',
                    'reserved_by_email'       => 'bob.reyes@sample.test',
                    'reserved_by_phone'       => '09171234002',
                    'number_of_people'        => 15,
                    'status'                  => 'approved',
                    'admin_remarks'           => 'Approved. Early morning slot. Cash payment due at cashier.',
                    'client_type_snapshot'    => 'internal',
                    'endorsement_status'      => 'not_required',
                    'total_price'             => 1800.00,
                    'amount_paid'             => 0,
                    'payment_status'          => 'cash_pending',
                    'payment_method'          => 'cash',
                    'payment_deadline'        => $date->copy()->subDays(2)->setTime(17, 0),
                    'security_deposit'        => 1000.00,
                    'security_deposit_paid'   => 0,
                    'security_deposit_status' => 'pending',
                    'is_fee_waived'           => false,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => now()->subDays(2),
                    'booking_reference'       => 'CASH-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'     => json_encode([
                        ['label' => 'Conference Room A (2 hrs × 1 day)', 'amount' => 1800],
                        ['label' => 'Security Deposit (refundable)', 'amount' => 1000],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'days'); // quick booking, payment pending
                $count++;
            }
        }

        // ── 3. David – Gymnasium, approved + partial cash paid ───────────────
        if ($david && $gym) {
            $date = $this->nextWeek->copy()->addDays(5);
            $slot = $this->findSlot($slots, 'Multi-Purpose Gymnasium', $date->dayOfWeek, '13:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $david->id,
                    'facility_id'             => $gym->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '13:00:00',
                    'end_time'                => '17:00:00',
                    'reason'                  => 'Cruz Events Management – Client Appreciation Day',
                    'event_title'             => 'CEM Client Appreciation Day',
                    'end_user_type'           => 'organization',
                    'end_user_name'           => 'Cruz Events Management',
                    'reserved_by_name'        => 'David Cruz',
                    'reserved_by_email'       => 'david.cruz@sample.test',
                    'reserved_by_phone'       => '09181234001',
                    'number_of_people'        => 120,
                    'status'                  => 'approved',
                    'admin_remarks'           => 'Approved. Partial cash deposit already paid. Balance due before event.',
                    'client_type_snapshot'    => 'external',
                    'endorsement_status'      => 'not_required',
                    'total_price'             => 14000.00,
                    'amount_paid'             => 7000.00,   // partial payment
                    'payment_status'          => 'cash_pending',
                    'payment_method'          => 'cash',
                    'payment_deadline'        => $date->copy()->subDay()->setTime(12, 0),
                    'security_deposit'        => 3000.00,
                    'security_deposit_paid'   => 3000.00,  // deposit already paid
                    'security_deposit_status' => 'paid',
                    'is_fee_waived'           => false,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => now()->subDays(4),
                    'cash_receipt_number'     => 'OR-2026-PARTIAL-001',
                    'cash_payment_notes'      => 'Initial 50% deposit paid. Remaining balance of ₱7,000 due on event day.',
                    'booking_reference'       => 'CASH-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'     => json_encode([
                        ['label' => 'Gymnasium (4 hrs × 1 day)',     'amount' => 14000],
                        ['label' => 'Security Deposit (refundable)', 'amount' => 3000],
                    ]),
                    'arrangements'            => json_encode([
                        ['item_id' => 4, 'name' => 'Sound System', 'quantity' => 1, 'unit_price' => 3000, 'subtotal' => 3000],
                        ['item_id' => 1, 'name' => 'Monoblock Chairs', 'quantity' => 120, 'unit_price' => 0, 'subtotal' => 0],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'weeks'); // submitted weeks prior, partial payment made
                $count++;
            }
        }

        // ── 4. Clara – Gymnasium, approved + cash pending (internal/waived dep) ─
        if ($clara && $gym) {
            $date = $this->twoWeeksOut->copy()->addDays(4);
            $slot = $this->findSlot($slots, 'Multi-Purpose Gymnasium', $date->dayOfWeek, '17:00');
            if ($slot) {
                $res = Reservation::create([
                    'user_id'                 => $clara->id,
                    'facility_id'             => $gym->id,
                    'start_date'              => $date->toDateString(),
                    'end_date'                => $date->toDateString(),
                    'start_time'              => '17:00:00',
                    'end_time'                => '21:00:00',
                    'reason'                  => 'OSA Year-End Recognition Ceremony and Awards Night',
                    'event_title'             => 'OSA Year-End Awards Night',
                    'end_user_type'           => 'organization',
                    'end_user_name'           => 'Office of Student Affairs',
                    'reserved_by_name'        => 'Clara Lim',
                    'reserved_by_email'       => 'clara.lim@sample.test',
                    'reserved_by_phone'       => '09171234003',
                    'number_of_people'        => 500,
                    'status'                  => 'approved',
                    'admin_remarks'           => 'Approved. Internal rate applies. Cash payment to be settled at cashier before event.',
                    'client_type_snapshot'    => 'internal',
                    'endorsement_status'      => 'endorsed',
                    'endorsed_by_name'        => 'Dr. Maria Santos',
                    'endorsed_by_position'    => 'Director, Office of Student Affairs',
                    'total_price'             => 12000.00,
                    'amount_paid'             => 0,
                    'payment_status'          => 'cash_pending',
                    'payment_method'          => 'cash',
                    'payment_deadline'        => $date->copy()->subDays(3)->setTime(17, 0),
                    'security_deposit'        => 0,
                    'security_deposit_status' => 'not_required',
                    'is_fee_waived'           => false,
                    'agreement_accepted'      => true,
                    'agreement_accepted_at'   => now()->subDays(5),
                    'booking_reference'       => 'CASH-' . strtoupper(Str::random(8)),
                    'quotation_breakdown'     => json_encode([
                        ['label' => 'Gymnasium (4 hrs × 1 day)', 'amount' => 12000],
                    ]),
                    'arrangements'            => json_encode([
                        ['item_id' => 4, 'name' => 'Sound System',     'quantity' => 1,   'unit_price' => 3000, 'subtotal' => 3000],
                        ['item_id' => 1, 'name' => 'Monoblock Chairs', 'quantity' => 500, 'unit_price' => 0,    'subtotal' => 0],
                        ['item_id' => 8, 'name' => 'Stage / Platform', 'quantity' => 1,   'unit_price' => 2000, 'subtotal' => 2000],
                    ]),
                ]);
                SlotReservation::create([
                    'reservation_id'        => $res->id,
                    'facility_time_slot_id' => $slot->id,
                    'reserved_date'         => $date->toDateString(),
                    'quantity_booked'       => 1,
                    'price_snapshot'        => $slot->price_per_hour,
                ]);
                $this->stampTimestamps($res, $date, 'weeks'); // booked weeks ahead, endorsed, payment at cashier
                $count++;
            }
        }

        $this->command->info("  ✔  Created {$count} cashier pending payment reservation(s).");
    }
}
