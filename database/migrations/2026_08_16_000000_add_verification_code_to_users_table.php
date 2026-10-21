<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds pre-login verification-code support to the users table.
     *   - verification_code: the 6-digit code (stored hashed, same
     *     convention as password_reset_tokens for a bit of defense).
     *   - verification_code_expires_at: 10-minute TTL per code.
     *   - code_verified_at: when the user successfully entered the code.
     *
     * External users get the code emailed automatically at registration.
     * Internal users also get a code, but additionally require administrator
     * approval (is_active) before they can log in.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('verification_code', 255)->nullable()->after('password');
            $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
            $table->timestamp('code_verified_at')->nullable()->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'verification_code_expires_at', 'code_verified_at']);
        });
    }
};
