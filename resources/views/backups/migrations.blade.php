@extends('layouts.app')

@section('title', 'Database Migrations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-database me-2"></i>Database Migrations</h2>
                <div>
                    <a href="{{ route('backups.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Backups
                    </a>
                    @if(count($pendingMigrations) > 0)
                        <form action="{{ route('migrations.run') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('Are you sure you want to run pending migrations? This will modify your database.')">
                                <i class="bi bi-play-circle me-2"></i>Run Migrations
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Pending Migrations ({{ count($pendingMigrations) }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(count($pendingMigrations) > 0)
                                <div class="list-group">
                                    @foreach($pendingMigrations as $migration)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-clock text-warning me-2"></i>
                                                <code>{{ $migration }}</code>
                                            </div>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        These migrations need to be run to update your database schema.
                                    </small>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-success">All migrations are up to date!</h5>
                                    <p class="text-muted">No pending migrations found.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-check-circle me-2"></i>
                                Completed Migrations ({{ count($ranMigrations) }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(count($ranMigrations) > 0)
                                <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                                    @foreach($ranMigrations as $migration)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <code>{{ $migration }}</code>
                                            </div>
                                            <span class="badge bg-success">Completed</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-database text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-muted">No migrations found</h5>
                                    <p class="text-muted">Database appears to be empty.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Migration Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>What are migrations?</h6>
                            <p class="text-muted">
                                Migrations are like version control for your database. They allow you to modify 
                                and share your application's database schema in a structured way.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>When to run migrations?</h6>
                            <ul class="text-muted">
                                <li>After installing new features</li>
                                <li>When updating the application</li>
                                <li>After pulling code changes</li>
                                <li>When database schema changes are needed</li>
                            </ul>
                        </div>
                    </div>
                    
                    @if(count($pendingMigrations) > 0)
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Always backup your database before running migrations 
                        if you have important data.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
