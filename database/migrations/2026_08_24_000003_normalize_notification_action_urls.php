<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('notifications')->select(['id', 'data'])->orderBy('id')->chunkById(100, function ($notifications): void {
            foreach ($notifications as $notification) {
                $data = json_decode((string) $notification->data, true);
                if (!is_array($data) || empty($data['action_url']) || !is_string($data['action_url'])) { continue; }
                $parts = parse_url($data['action_url']);
                $host = is_array($parts) ? strtolower((string) ($parts['host'] ?? '')) : '';
                if (!in_array($host, ['localhost', '127.0.0.1', '::1'], true)) { continue; }
                $relative = $parts['path'] ?? '/';
                if (isset($parts['query'])) { $relative .= '?' . $parts['query']; }
                if (isset($parts['fragment'])) { $relative .= '#' . $parts['fragment']; }
                $data['action_url'] = $relative;
                DB::table('notifications')->where('id', $notification->id)->update(['data' => json_encode($data, JSON_UNESCAPED_SLASHES)]);
            }
        });
    }

    public function down(): void
    {
        // Relative destinations are intentionally not converted back to a host-specific URL.
    }
};
