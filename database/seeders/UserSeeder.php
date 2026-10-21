<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Idempotent user seeder.
     *
     * Safe to re-run at any time: uses updateOrCreate() keyed on email, so it
     * recreates missing accounts after a DB wipe without throwing on existing
     * rows. Passwords are hashed by the User model's 'hashed' cast.
     *
     * Override default passwords via env before seeding, e.g.:
     *   SEED_SUPERADMIN_PASSWORD=... php artisan db:seed --class=UserSeeder
     */
    public function run(): void
    {
        foreach ($this->accounts() as $account) {
            $password = $account['password'];
            unset($account['password']);

            User::updateOrCreate(
                ['email' => $account['email']],
                array_merge($account, [
                    'password'          => $password,
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ])
            );
        }
    }

    /**
     * The baseline accounts that must always exist.
     *
     * @return array<int, array<string, mixed>>
     */
    private function accounts(): array
    {
        return [
            [
                'name'       => 'Super Admin',
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'email'      => 'superadmin@sure-system.com',
                'role'       => User::ROLE_SUPERADMIN,
                'password'   => env('SEED_SUPERADMIN_PASSWORD', 'superadmin123'),
            ],
            [
                'name'       => 'Admin User',
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'email'      => 'admin@sure-system.com',
                'role'       => User::ROLE_ADMIN,
                'password'   => env('SEED_ADMIN_PASSWORD', 'admin123'),
            ],
            [
                'name'       => 'Cashier User',
                'first_name' => 'Cashier',
                'last_name'  => 'User',
                'email'      => 'cashier@sure-system.com',
                'role'       => User::ROLE_CASHIER,
                'password'   => env('SEED_CASHIER_PASSWORD', 'cashier123'),
            ],
            [
                'name'       => 'Staff User',
                'first_name' => 'Staff',
                'last_name'  => 'User',
                'email'      => 'staff@sure-system.com',
                'role'       => User::ROLE_STAFF,
                'password'   => env('SEED_STAFF_PASSWORD', 'staff123'),
            ],
            [
                'name'       => 'Guest User',
                'first_name' => 'Guest',
                'last_name'  => 'User',
                'email'      => 'guest@sure-system.com',
                'role'       => User::ROLE_GUEST,
                'password'   => env('SEED_GUEST_PASSWORD', 'guest123'),
            ],
        ];
    }
}
