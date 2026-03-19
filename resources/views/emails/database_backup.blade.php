<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; font-size: 14px; }
        .container { max-width: 560px; margin: 30px auto; padding: 24px; border: 1px solid #e0e0e0; border-radius: 6px; }
        h2 { color: #1a73e8; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 8px 10px; border: 1px solid #e0e0e0; }
        td:first-child { font-weight: bold; background: #f5f5f5; width: 140px; }
        .footer { margin-top: 20px; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <h2>&#128197; Daily Database Backup</h2>
        <p>Your scheduled database backup has been completed successfully. The backup file is attached to this email.</p>

        <table>
            <tr><td>File Name</td><td>{{ $filename }}</td></tr>
            <tr><td>File Size</td><td>{{ $size }}</td></tr>
            <tr><td>Created At</td><td>{{ $date }}</td></tr>
            <tr><td>Application</td><td>{{ config('app.name') }}</td></tr>
        </table>

        <p style="margin-top: 18px;">Only the <strong>3 most recent backups</strong> are retained on the server. Older files are automatically removed.</p>

        <div class="footer">
            This is an automated message from {{ config('app.name') }} — please do not reply.
        </div>
    </div>
</body>
</html>
