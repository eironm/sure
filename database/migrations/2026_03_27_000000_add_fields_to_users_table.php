<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $column) {
            $column->string('first_name')->nullable();
            $column->string('last_name')->nullable();
            $column->string('role')->default('guest'); // guest, admin
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $column) {
            $column->dropColumn(['first_name', 'last_name', 'role']);
        });
    }
};
