<?php

namespace App\Console\Commands;

use App\Mail\DatabaseBackupMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Create a MySQL database backup, keep only the last 3, and email it';

    // Maximum number of backup files to retain
    private const MAX_BACKUPS = 3;

    public function handle(): int
    {
        $backupDir = storage_path('app/backups');

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Build mysqldump command using DB credentials from config
        $host     = config('database.connections.mysql.host');
        $port     = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Allow overriding the mysqldump binary path via MYSQLDUMP_PATH env var
        // e.g. C:\xampp\mysql\bin\mysqldump.exe or /usr/bin/mysqldump
        $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');

        // Escape password for shell use
        $escapedPassword = escapeshellarg($password);

        $command = escapeshellarg($mysqldump) . " --host={$host} --port={$port} --user={$username} --password={$escapedPassword} {$database} > " . escapeshellarg($filepath) . " 2>&1";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || ! file_exists($filepath) || filesize($filepath) === 0) {
            $this->error('Database backup failed. Check mysqldump output: ' . implode("\n", $output));
            return self::FAILURE;
        }

        $this->info("Backup created: {$filename}");

        // Remove old backups beyond the MAX_BACKUPS limit
        $this->pruneOldBackups($backupDir);

        // Email the backup
        $recipient = config('backup.mail_to', env('BACKUP_MAIL_TO'));

        if (empty($recipient)) {
            $this->warn('BACKUP_MAIL_TO is not set — skipping email.');
        } else {
            Mail::to($recipient)->send(new DatabaseBackupMail($filepath, $filename));
            $this->info("Backup emailed to {$recipient}");
        }

        return self::SUCCESS;
    }

    private function pruneOldBackups(string $backupDir): void
    {
        // Collect all .sql backup files sorted from newest to oldest
        $files = glob($backupDir . DIRECTORY_SEPARATOR . 'backup_*.sql');

        if ($files === false || count($files) <= self::MAX_BACKUPS) {
            return;
        }

        // Sort descending by filename (timestamps sort lexicographically)
        rsort($files);

        $toDelete = array_slice($files, self::MAX_BACKUPS);

        foreach ($toDelete as $old) {
            unlink($old);
            $this->info('Removed old backup: ' . basename($old));
        }
    }
}
