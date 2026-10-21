<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix: ensure the `id` column on all core tables has AUTO_INCREMENT on MySQL.
 *
 * When a SQLite database is exported/imported into MySQL (common on shared
 * hosting), the AUTO_INCREMENT property is often lost because SQLite uses
 * INTEGER PRIMARY KEY (implicit rowid) instead of an explicit AUTO_INCREMENT.
 * This migration patches any affected tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Only relevant for MySQL / MariaDB
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $tables = [
            'users'               => 'BIGINT UNSIGNED',
            'audit_logs'          => 'BIGINT UNSIGNED',
            'facilities'          => 'BIGINT UNSIGNED',
            'reservations'        => 'BIGINT UNSIGNED',
            'notifications'       => 'BIGINT UNSIGNED',
            'password_histories'  => 'BIGINT UNSIGNED',
        ];

        foreach ($tables as $table => $type) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            // Check if id column already has auto_increment
            $column = DB::selectOne(
                "SELECT EXTRA FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = ?
                   AND COLUMN_NAME = 'id'",
                [$table]
            );

            if ($column && stripos($column->EXTRA ?? '', 'auto_increment') === false) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `id` {$type} NOT NULL AUTO_INCREMENT");
            }
        }
    }

    public function down(): void
    {
        // Intentional no-op — removing AUTO_INCREMENT would break everything.
    }
};
