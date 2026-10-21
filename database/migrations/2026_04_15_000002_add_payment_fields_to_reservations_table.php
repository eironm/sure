<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('total_price');
            // unpaid, pending, paid, failed, refunded
            $table->string('payment_id')->nullable()->after('payment_status');
            // PayMongo checkout session or payment intent ID
            $table->string('payment_method')->nullable()->after('payment_id');
            // gcash, grab_pay, paymaya, card, etc.
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_id', 'payment_method']);
        });
    }
};
