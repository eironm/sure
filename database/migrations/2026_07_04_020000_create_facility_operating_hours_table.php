<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the facility_operating_hours table used by App\Models\FacilityOperatingHour.
 * This table was previously missing a migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('facility_operating_hours')) {
            return;
        }

        Schema::create('facility_operating_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 0 = Sunday ... 6 = Saturday
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            $table->unique(['facility_id', 'day_of_week'], 'fac_op_hours_facility_day_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_operating_hours');
    }
};
