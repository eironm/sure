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
        Schema::table('users', function (Blueprint $table) {
            $table->string('client_type')->nullable(); // 'internal' or 'external'
            $table->string('contact_number')->nullable();
            
            // Internal specific fields
            $table->string('university_email')->nullable();
            $table->string('university_id')->nullable();
            $table->string('affiliation_type')->nullable(); // Student, Faculty, Admin/Staff
            $table->string('college_department')->nullable();
            
            // External specific fields
            $table->date('date_of_birth')->nullable();
            $table->string('organization_name')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('government_id_type')->nullable();
            $table->string('government_id_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'client_type',
                'contact_number',
                'university_email',
                'university_id',
                'affiliation_type',
                'college_department',
                'date_of_birth',
                'organization_name',
                'billing_address',
                'government_id_type',
                'government_id_number',
            ]);
        });
    }
};
