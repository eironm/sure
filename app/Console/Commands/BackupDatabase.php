<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Creates a timestamped backup of the database.
 *
 * - SQLite: copies the .sqlite file (fast, exact).
 * - MySQL/MariaDB: runs mysqldump (works on hosts where the binary is available).
 *
 * Backups are written to storage/app/backups and the newest N are kept.
 *
 * Usage:
 *   php artisan db:backup
 *   php artisan db:backup --keep=30
 */
class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--keep=15 : How many recent backups to retain}';

    protected $description = 'Create a timestamped backup of the current database';

    public function handle(): int
    {
        $connection = config('database.default');
        $backupDir  = storage_path('app/backups');

        File::ensureDirectoryExists($backupDir);

        $timestamp = now()->format('Y_m_d_His');

        $result = match ($connection) {
            'sqlite'         => $this->backupSqlite($backupDir, $timestamp),
            'mysql', 'mariadb' => $this->backupMysql($backupDir, $timestamp),
            default          => $this->unsupported($connection),
        };

        if ($result !== self::SUCCESS) {
            return $result;
        }

        $this->prune($backupDir, (int) $this->option('keep'));

        return self::SUCCESS;
    }

    private function backupSqlite(string $backupDir, string $timestamp): int
    {
        $source = config('database.connections.sqlite.database');

        if (! $source || ! File::exists($source)) {
            $this->error("SQLite database file not found: {$source}");
            return self::FAILURE;
        }

        $destination = $backupDir . DIRECTORY_SEPARATOR . "backup_{$timestamp}.sqlite";

        if (! File::copy($source, $destination)) {
            $this->error('Failed to copy the SQLite database file.');
            return self::FAILURE;
        }

        $this->info("SQLite backup created: {$destination}");
        return self::SUCCESS;
    }

    private function backupMysql(string $backupDir, string $timestamp): int
    {
        $cfg = config('database.connections.' . config('database.default'));

        $destination = $backupDir . DIRECTORY_SEPARATOR . "backup_{$timestamp}.sql";

        // Pass the password via env so it never appears in the process list.
        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s > %s',
            escapeshellarg((string) $cfg['host']),
            escapeshellarg((string) $cfg['port']),
            escapeshellarg((string) $cfg['username']),
            escapeshellarg((string) $cfg['database']),
            escapeshellarg($destination)
        );

        $env = ['MYSQL_PWD' => (string) ($cfg['password'] ?? '')];

        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null, array_merge(getenv(), $env));

        if (! is_resource($process)) {
            $this->error('Failed to start mysqldump. Is the binary installed and on PATH?');
            return self::FAILURE;
        }

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            $this->error('mysqldump failed: ' . trim($stderr));
            return self::FAILURE;
        }

        $this->info("MySQL backup created: {$destination}");
        return self::SUCCESS;
    }

    private function unsupported(string $connection): int
    {
        $this->error("Unsupported database connection for backup: {$connection}");
        return self::FAILURE;
    }

    /**
     * Keep only the newest $keep backups; delete the rest.
     */
    private function prune(string $backupDir, int $keep): void
    {
        if ($keep <= 0) {
            return;
        }

        $files = collect(File::files($backupDir))
            ->filter(fn ($f) => str_starts_with($f->getFilename(), 'backup_'))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->values();

        $files->slice($keep)->each(function ($f) {
            File::delete($f->getPathname());
            $this->line("Pruned old backup: {$f->getFilename()}");
        });
    }
}
