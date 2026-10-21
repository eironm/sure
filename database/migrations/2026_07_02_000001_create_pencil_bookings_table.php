<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencil_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('note', 255)->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            // One active pencil hold per user, per facility, per date
            $table->unique(['user_id', 'facility_id', 'date']);
            $table->index(['facility_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencil_bookings');
    }
};
