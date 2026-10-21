<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the facility_tag pivot table for the many-to-many relationship
 * between App\Models\Facility and App\Models\FacilityTag.
 *
 * Column names (facility_id, tag_id) match the belongsToMany definitions in
 * both models. This pivot was previously missing a migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('facility_tag')) {
            return;
        }

        Schema::create('facility_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('facility_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['facility_id', 'tag_id'], 'facility_tag_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_tag');
    }
};
