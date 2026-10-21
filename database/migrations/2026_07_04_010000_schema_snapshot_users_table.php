<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SCHEMA SNAPSHOT — users table (reference / disaster-recovery).
 *
 * This is a single, consolidated definition of the FULL `users` table as it
 * exists after all the incremental add_*_to_users migrations. It is intended
 * as documentation and as a one-shot rebuild path on a brand-new database.
 *
 * SQLite note: this project currently uses SQLite. SQLite has limited ALTER
 * TABLE support, so the incremental migrations are the source of truth for
 * upgrades. This snapshot only runs when `users` does NOT already exist, so it
 * will never clash with an existing schema. On your real DB (where `users` was
 * built by the earlier migrations) this migration is a no-op.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Guard: never touch an existing users table.
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            // Core auth
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Name parts
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();

            // Role & status
            $table->string('role')->default('guest'); // guest, cashier, staff, admin, superadmin
            $table->boolean('is_active')->default(true);

            // Client / contact
            $table->string('client_type')->nullable(); // internal / external
            $table->string('contact_number')->nullable();

            // Internal-specific
            $table->string('university_email')->nullable();
            $table->string('university_id')->nullable();
            $table->string('affiliation_type')->nullable();
            $table->string('college_department')->nullable();

            // External-specific
            $table->date('date_of_birth')->nullable();
            $table->string('organization_name')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('government_id_type')->nullable();
            $table->string('government_id_number')->nullable();

            // Universal personal identity
            $table->string('sex')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('nationality')->nullable();
            $table->text('personal_address')->nullable();

            // Profile
            $table->string('avatar')->nullable();
        });
    }

    public function down(): void
    {
        // Intentionally a no-op. This snapshot must never drop a users table it
        // may not have created. Manage teardown via migrate:fresh in dev only.
    }
};
