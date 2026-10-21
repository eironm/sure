<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds pencil-book, letter, and rental-agreement fields to the
     * reservations table for the full digital workflow upgrade.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // ── Pencil Book Fields ──────────────────────────────
            $table->string('event_title')->nullable()->after('reason');
            $table->string('end_user_type')->nullable()->after('event_title');          // 'college' or 'department' or 'organization'
            $table->string('end_user_name')->nullable()->after('end_user_type');        // Name of college/department/org
            $table->string('reserved_by_name')->nullable()->after('end_user_name');     // Contact person full name
            $table->string('reserved_by_email')->nullable()->after('reserved_by_name'); // Contact person email
            $table->string('reserved_by_phone')->nullable()->after('reserved_by_email');// Contact person phone

            // ── Endorsement Chain ───────────────────────────────
            $table->string('endorsed_by_name')->nullable()->after('reserved_by_phone');
            $table->string('endorsed_by_position')->nullable()->after('endorsed_by_name');
            $table->string('endorsement_status')->default('not_required')->after('endorsed_by_position');
            // Values: not_required, pending, endorsed, waived

            // ── User Type & Pricing ─────────────────────────────
            $table->string('client_type_snapshot')->nullable()->after('endorsement_status');  // 'internal' or 'external'
            $table->boolean('is_fee_waived')->default(false)->after('client_type_snapshot');

            // ── Security Deposit ────────────────────────────────
            $table->decimal('security_deposit', 10, 2)->default(0)->after('is_fee_waived');
            $table->decimal('security_deposit_paid', 10, 2)->default(0)->after('security_deposit');
            $table->string('security_deposit_status')->default('not_required')->after('security_deposit_paid');
            // Values: not_required, pending, paid, refunded, forfeited

            // ── Logistical Requirements (JSON) ──────────────────
            $table->json('arrangements')->nullable()->after('security_deposit_status');
            // e.g. [{"item_id":1,"name":"Chairs","quantity":50,"unit_price":0,"subtotal":0}]

            // ── Quotation Breakdown (JSON) ──────────────────────
            $table->json('quotation_breakdown')->nullable()->after('arrangements');
            // e.g. [{"label":"Venue (8hrs × 2 days)","amount":16000},{"label":"Sound System","amount":3000}]

            // ── Agreement & Cancellation ────────────────────────
            $table->boolean('agreement_accepted')->default(false)->after('quotation_breakdown');
            $table->timestamp('agreement_accepted_at')->nullable()->after('agreement_accepted');
            $table->string('cancellation_reason')->nullable()->after('agreement_accepted_at');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');

            // ── Official Receipt # ──────────────────────────────
            $table->string('official_receipt_number')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'event_title',
                'end_user_type',
                'end_user_name',
                'reserved_by_name',
                'reserved_by_email',
                'reserved_by_phone',
                'endorsed_by_name',
                'endorsed_by_position',
                'endorsement_status',
                'client_type_snapshot',
                'is_fee_waived',
                'security_deposit',
                'security_deposit_paid',
                'security_deposit_status',
                'arrangements',
                'quotation_breakdown',
                'agreement_accepted',
                'agreement_accepted_at',
                'cancellation_reason',
                'cancelled_at',
                'official_receipt_number',
            ]);
        });
    }
};
