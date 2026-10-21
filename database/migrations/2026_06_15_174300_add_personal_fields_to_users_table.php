<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Universal personal identity fields (all users)
            $table->string('sex')->nullable();            // Male / Female
            $table->string('civil_status')->nullable();   // Single / Married / Widowed / Separated
            $table->string('nationality')->nullable();    // e.g., Filipino
            $table->text('personal_address')->nullable(); // Home/permanent address
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sex', 'civil_status', 'nationality', 'personal_address']);
        });
    }
};
