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
        Schema::table('reservations', function (Blueprint $table) {
            // Add time-based fields for enhanced booking tracking
            $table->time('start_time')->nullable()->after('start_date')->comment('Start time of the reservation');
            $table->time('end_time')->nullable()->after('end_date')->comment('End time of the reservation');
            $table->decimal('total_price', 10, 2)->nullable()->after('admin_remarks')->comment('Total price at booking time');
            $table->string('booking_reference')->unique()->nullable()->after('total_price')->comment('Unique booking reference for confirmation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time', 'total_price', 'booking_reference']);
        });
    }
};
