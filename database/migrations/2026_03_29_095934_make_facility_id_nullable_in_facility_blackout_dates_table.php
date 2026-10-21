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
        if (!Schema::hasTable('facility_blackout_dates')) {
            Schema::create('facility_blackout_dates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('facility_id')->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->string('reason')->nullable();
                $table->boolean('is_recurring')->default(false);
                $table->string('recurring_pattern')->nullable();
                $table->timestamps();

                $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            });
        } else {
            Schema::table('facility_blackout_dates', function (Blueprint $table) {
                $table->unsignedBigInteger('facility_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_blackout_dates');
    }
};
