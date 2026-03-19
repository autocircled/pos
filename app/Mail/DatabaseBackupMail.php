<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DatabaseBackupMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly string $filepath,
        private readonly string $filename,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Database Backup — ' . date('d M Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.database_backup',
            with: [
                'filename' => $this->filename,
                'size'     => $this->humanFilesize(filesize($this->filepath)),
                'date'     => date('d M Y, h:i A'),
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->filepath)
                ->as($this->filename)
                ->withMime('application/sql'),
        ];
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
