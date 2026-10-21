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
        Schema::create('slot_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('facility_time_slot_id')->constrained('facility_time_slots')->onDelete('cascade');
            $table->date('reserved_date')->comment('The specific date this slot is reserved for');
            $table->unsignedInteger('quantity_booked')->default(1)->comment('Number of slots booked (typically 1)');
            $table->decimal('price_snapshot', 10, 2)->nullable()->comment('Price at time of booking for audit trail');
            $table->timestamps();

            // Composite unique: one reservation per slot per date
            // Explicit name: auto-generated name (75 chars) exceeds MySQL's 64-char identifier limit
            $table->unique(['reservation_id', 'facility_time_slot_id', 'reserved_date'], 'slot_res_reservation_slot_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_reservations');
    }
};
