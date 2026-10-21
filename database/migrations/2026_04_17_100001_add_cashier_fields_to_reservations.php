<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Cashier who confirmed the cash payment
            $table->foreignId('cashier_signed_by')
                  ->nullable()
                  ->after('payment_method')
                  ->constrained('users')
                  ->nullOnDelete();

            // When the cashier confirmed it
            $table->timestamp('cashier_signed_at')->nullable()->after('cashier_signed_by');

            // Optional note the cashier can attach (e.g. OR number)
            $table->string('cash_receipt_number')->nullable()->after('cashier_signed_at');
            $table->text('cash_payment_notes')->nullable()->after('cash_receipt_number');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['cashier_signed_by']);
            $table->dropColumn(['cashier_signed_by', 'cashier_signed_at', 'cash_receipt_number', 'cash_payment_notes']);
        });
    }
};
