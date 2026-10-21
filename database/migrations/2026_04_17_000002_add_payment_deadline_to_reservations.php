<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Absolute deadline timestamp for payment
            // Digital: created_at + 24h  |  Cash: created_at + 72h
            $table->timestamp('payment_deadline')->nullable()->after('payment_method');

            // payment_method already exists; add cash_pending to payment_status semantics via a comment —
            // the column is a plain string so any value is valid without an ENUM migration.
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('payment_deadline');
        });
    }
};
