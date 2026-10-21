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
        if (!Schema::hasTable('facility_images')) {
            Schema::create('facility_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
                $table->text('image_url')->nullable();
                $table->string('image_path')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
            });
        } else {
            Schema::table('facility_images', function (Blueprint $table) {
                if (!Schema::hasColumn('facility_images', 'image_path')) {
                    $table->string('image_path')->nullable()->after('image_url');
                }
                $table->string('image_url')->nullable(true)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_images');
    }
};
