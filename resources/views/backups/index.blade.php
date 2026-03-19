@extends('layouts.app')

@section('title', 'Database Backups')
@section('page-title', 'Database Backups')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-database me-2"></i>Backup Files</span>
                <form action="{{ route('backups.run') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm"
                            onclick="return confirm('Create a new database backup now?')">
                        <i class="bi bi-plus-circle me-1"></i> Run Backup Now
                    </button>
                </form>
            </div>

            <div class="card-body p-0">
                @if(empty($backups))
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p class="mb-0">No backups found.</p>
                        <small>Click <strong>Run Backup Now</strong> to create the first backup.</small>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>File Name</th>
                                    <th>Created</th>
                                    <th>Size</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $index => $backup)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <i class="bi bi-file-earmark-code me-2 text-primary"></i>
                                        <span class="fw-semibold">{{ $backup['filename'] }}</span>
                                        @if($index === 0)
                                            <span class="badge ms-2" style="background:#dcfce7;color:#16a34a;font-size:0.7rem;">Latest</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $backup['date'] }}</td>
                                    <td class="text-muted">{{ $backup['size'] }}</td>
                                    <td class="text-end">
                                        {{-- Download button --}}
                                        <a href="{{ route('backups.download', $backup['filename']) }}"
                                           class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>

                                        {{-- Restore button --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-warning me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#restoreModal"
                                                data-filename="{{ $backup['filename'] }}">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                                        </button>

                                        {{-- Delete button --}}
                                        <form action="{{ route('backups.destroy', $backup['filename']) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this backup? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="card-footer text-muted small">
                <i class="bi bi-info-circle me-1"></i>
                Only the last <strong>3 backups</strong> are kept on the server. Older files are removed automatically.
                Backups run daily at <strong>11:00 PM</strong>.
            </div>
        </div>
    </div>
</div>

{{-- Restore Confirmation Modal --}}
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="restoreModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Confirm Restore
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">You are about to restore the database from:</p>
                <p class="fw-semibold text-primary mb-3" id="restoreFilename">—</p>
                <div class="alert alert-warning py-2 mb-0">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <strong>Warning:</strong> This will overwrite all current data with the backup.
                    This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="restoreForm" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Yes, Restore
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Populate the restore modal with the correct filename and form action
    document.getElementById('restoreModal').addEventListener('show.bs.modal', function (event) {
        const filename = event.relatedTarget.dataset.filename;
        document.getElementById('restoreFilename').textContent = filename;
        document.getElementById('restoreForm').action = '/backups/' + encodeURIComponent(filename) + '/restore';
    });
</script>
@endpush
