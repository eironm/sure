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
        Schema::create('facility_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Link facilities to a category (nullable so existing rows are unaffected).
        if (Schema::hasTable('facilities') && ! Schema::hasColumn('facilities', 'category_id')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('facility_categories')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('facilities') && Schema::hasColumn('facilities', 'category_id')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->dropConstrainedForeignId('category_id');
            });
        }

        Schema::dropIfExists('facility_categories');
    }
};
