<?php

use App\Models\SystemSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (! SystemSetting::where('key', 'legal_documents_updated_at')->exists()) {
            SystemSetting::create([
                'key'   => 'legal_documents_updated_at',
                'value' => now()->toIso8601String(),
                'type'  => 'text',
                'group' => 'legal',
                'label' => 'Legal Documents Last Updated',
            ]);
        }
    }

    public function down(): void
    {
        SystemSetting::where('key', 'legal_documents_updated_at')->delete();
    }
};

