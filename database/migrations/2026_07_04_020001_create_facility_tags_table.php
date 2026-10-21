<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the facility_tags table used by App\Models\FacilityTag.
 * This table was previously missing a migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('facility_tags')) {
            return;
        }

        Schema::create('facility_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_tags');
    }
};
