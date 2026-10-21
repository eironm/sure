<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds facility detail columns that the admin create/edit forms and the
 * Facility model's $fillable reference, but which were never created by a
 * migration. Without these, creating or editing a facility throws
 * "table facilities has no column named amenities" and nothing is saved.
 *
 * Guarded with hasColumn() so it is safe on any existing database.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (!Schema::hasColumn('facilities', 'amenities')) {
                $table->text('amenities')->nullable()->after('is_available');
            }
            if (!Schema::hasColumn('facilities', 'operating_hours')) {
                $table->string('operating_hours')->nullable()->after('amenities');
            }
            if (!Schema::hasColumn('facilities', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('operating_hours');
            }
            if (!Schema::hasColumn('facilities', 'contact_phone')) {
                $table->string('contact_phone', 30)->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('facilities', 'terms_conditions')) {
                $table->text('terms_conditions')->nullable()->after('contact_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            foreach (['terms_conditions', 'contact_phone', 'contact_person', 'operating_hours', 'amenities'] as $col) {
                if (Schema::hasColumn('facilities', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
