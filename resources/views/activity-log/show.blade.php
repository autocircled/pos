@extends('layouts.app')

@section('title', 'Activity Log Entry')
@section('page-title', 'Activity Log Entry')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-journal-text me-2"></i>Log Details
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="140">Time</th>
                        <td>{{ $activityLog->created_at->format('M j, Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $activityLog->user?->name ?? 'System' }} @if($activityLog->user)<span class="text-muted">({{ $activityLog->user->email }})</span>@endif</td>
                    </tr>
                    <tr>
                        <th>Action</th>
                        <td>
                            @if($activityLog->action === 'created')
                                <span class="badge bg-success">Created</span>
                            @elseif($activityLog->action === 'updated')
                                <span class="badge bg-primary">Updated</span>
                            @else
                                <span class="badge bg-danger">Deleted</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td>{{ class_basename($activityLog->subject_type) }} @if($activityLog->subject_id) #{{ $activityLog->subject_id }} @endif</td>
                    </tr>
                    @if($activityLog->description)
                        <tr>
                            <th>Description</th>
                            <td>{{ $activityLog->description }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        @if($activityLog->action === 'updated' && $activityLog->getChangesSummary())
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-arrow-left-right me-2"></i>Changes
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Fields that were modified in this update:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Old value</th>
                                    <th>New value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityLog->getChangesSummary() as $field => $change)
                                    <tr>
                                        <td class="fw-semibold">{{ str_replace('_', ' ', ucfirst($field)) }}</td>
                                        <td><code>{{ is_bool($change['old']) ? ($change['old'] ? 'Yes' : 'No') : (string) $change['old'] }}</code></td>
                                        <td><code>{{ is_bool($change['new']) ? ($change['new'] ? 'Yes' : 'No') : (string) $change['new'] }}</code></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($activityLog->action === 'created' && $activityLog->new_values)
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-plus-circle me-2"></i>Created data
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityLog->new_values as $field => $value)
                                    @if(!in_array($field, ['created_at', 'updated_at']))
                                        <tr>
                                            <td class="fw-semibold">{{ str_replace('_', ' ', ucfirst($field)) }}</td>
                                            <td><code>{{ is_bool($value) ? ($value ? 'Yes' : 'No') : (string) $value }}</code></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($activityLog->action === 'deleted' && $activityLog->old_values)
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-trash me-2"></i>Deleted data (snapshot)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityLog->old_values as $field => $value)
                                    @if(!in_array($field, ['created_at', 'updated_at']))
                                        <tr>
                                            <td class="fw-semibold">{{ str_replace('_', ' ', ucfirst($field)) }}</td>
                                            <td><code>{{ is_bool($value) ? ($value ? 'Yes' : 'No') : (string) $value }}</code></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('activity-log.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Activity Log
            </a>
        </div>
    </div>
</div>
@endsection
