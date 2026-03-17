@extends('layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('content')
<div class="mb-4">
    <p class="text-muted mb-0">View actions performed by users (e.g. product edits and changes).</p>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('activity-log.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="user" class="form-select">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="subject" class="form-select">
                    <option value="">All Subjects</option>
                    <option value="App\Models\Product" {{ request('subject') === 'App\Models\Product' ? 'selected' : '' }}>Product</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th width="80"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M j, Y H:i') }}</td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td>
                                @if($log->action === 'created')
                                    <span class="badge bg-success">Created</span>
                                @elseif($log->action === 'updated')
                                    <span class="badge bg-primary">Updated</span>
                                @else
                                    <span class="badge bg-danger">Deleted</span>
                                @endif
                            </td>
                            <td>
                                @if($log->subject_type === 'App\Models\Product')
                                    Product
                                    @if($log->subject_id)
                                        #{{ $log->subject_id }}
                                    @endif
                                @else
                                    {{ class_basename($log->subject_type) }}
                                @endif
                            </td>
                            <td>{{ $log->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('activity-log.show', $log) }}" class="btn btn-outline-primary btn-sm" title="View details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
        <div class="card-footer">
            {{ $logs->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
