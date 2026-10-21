<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add draft-upload support and manual ordering to facility images.
     *
     * - draft_token: lets images be uploaded (and stored) BEFORE the parent
     *   facility exists (Create flow). Once the facility is saved, matching
     *   drafts are claimed and the token is cleared.
     * - sort_order: enables drag-to-reorder of the gallery.
     */
    public function up(): void
    {
        Schema::table('facility_images', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_images', 'draft_token')) {
                $table->string('draft_token', 64)->nullable()->after('facility_id')->index();
            }
            if (!Schema::hasColumn('facility_images', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_featured');
            }
        });

        // Facility must be nullable so a draft image can exist before its parent.
        if (Schema::hasColumn('facility_images', 'facility_id')) {
            Schema::table('facility_images', function (Blueprint $table) {
                $table->unsignedBigInteger('facility_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('facility_images', function (Blueprint $table) {
            if (Schema::hasColumn('facility_images', 'draft_token')) {
                $table->dropColumn('draft_token');
            }
            if (Schema::hasColumn('facility_images', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
