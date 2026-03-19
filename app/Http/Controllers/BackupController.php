<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class BackupController extends Controller
{
    private string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
    }

    public function index(): View
    {
        $backups = [];

        if (is_dir($this->backupDir)) {
            $files = glob($this->backupDir . DIRECTORY_SEPARATOR . 'backup_*.sql') ?: [];

            // Sort newest first
            rsort($files);

            foreach ($files as $path) {
                $bytes = filesize($path);
                $backups[] = [
                    'filename' => basename($path),
                    'path'     => $path,
                    'size'     => $this->humanFilesize($bytes),
                    'date'     => date('d M Y, h:i A', filemtime($path)),
                ];
            }
        }

        return view('backups.index', compact('backups'));
    }

    public function run(): RedirectResponse
    {
        try {
            $exitCode = Artisan::call('db:backup');

            if ($exitCode === 0) {
                return redirect()->route('backups.index')
                    ->with('success', 'Database backup created and emailed successfully.');
            }

            return redirect()->route('backups.index')
                ->with('error', 'Backup command failed. Check server logs for details.');
        } catch (\Throwable $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restore(string $filename): RedirectResponse
    {
        // Validate filename to prevent path traversal
        if (! preg_match('/^backup_[\d_\-]+\.sql$/', $filename)) {
            return redirect()->route('backups.index')
                ->with('error', 'Invalid backup filename.');
        }

        $filepath = $this->backupDir . DIRECTORY_SEPARATOR . $filename;

        if (! file_exists($filepath)) {
            return redirect()->route('backups.index')
                ->with('error', 'Backup file not found.');
        }

        $host     = config('database.connections.mysql.host');
        $port     = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Derive mysql binary from MYSQLDUMP_PATH (replace mysqldump → mysql)
        $mysqldumpBin = env('MYSQLDUMP_PATH', 'mysqldump');
        $mysqlBin     = preg_replace('/mysqldump(\.exe)?$/i', 'mysql$1', $mysqldumpBin);

        $escapedPassword = escapeshellarg($password);

        $command = escapeshellarg($mysqlBin)
            . " --host={$host} --port={$port} --user={$username} --password={$escapedPassword}"
            . " {$database} < " . escapeshellarg($filepath) . " 2>&1";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            return redirect()->route('backups.index')
                ->with('error', 'Restore failed: ' . implode(' ', $output));
        }

        return redirect()->route('backups.index')
            ->with('success', "Database restored successfully from {$filename}.");
    }

    public function download(string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
    {
        if (! preg_match('/^backup_[\d_\-]+\.sql$/', $filename)) {
            return redirect()->route('backups.index')
                ->with('error', 'Invalid backup filename.');
        }

        $filepath = $this->backupDir . DIRECTORY_SEPARATOR . $filename;

        if (! file_exists($filepath)) {
            return redirect()->route('backups.index')
                ->with('error', 'Backup file not found.');
        }

        return response()->download($filepath, $filename, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    public function destroy(string $filename): RedirectResponse
    {
        // Validate filename to prevent path traversal
        if (! preg_match('/^backup_[\d_\-]+\.sql$/', $filename)) {
            return redirect()->route('backups.index')
                ->with('error', 'Invalid backup filename.');
        }

        $filepath = $this->backupDir . DIRECTORY_SEPARATOR . $filename;

        if (! file_exists($filepath)) {
            return redirect()->route('backups.index')
                ->with('error', 'Backup file not found.');
        }

        unlink($filepath);

        return redirect()->route('backups.index')
            ->with('success', "Backup {$filename} deleted.");
    }

    private function humanFilesize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
