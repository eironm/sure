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
        Schema::create('reservation_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // MIME type, e.g. application/pdf, image/jpeg
            $table->unsignedBigInteger('file_size')->default(0); // size in bytes
            $table->string('document_type')->default('supporting_document'); // e.g. supporting_document, request_letter, proposal
            $table->timestamps();

            $table->index(['reservation_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_documents');
    }
};
