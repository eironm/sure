<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facility_time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->unsignedTinyInteger('day_of_week')->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('capacity')->default(1)->comment('Number of simultaneous bookings allowed');
            $table->decimal('price_per_hour', 10, 2)->nullable()->comment('Override facility price if set');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Composite unique constraint: facility + day + time window
            // Explicit name: auto-generated name (70 chars) exceeds MySQL's 64-char identifier limit
            $table->unique(['facility_id', 'day_of_week', 'start_time', 'end_time'], 'fts_facility_day_time_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_time_slots');
    }
};
