@extends('layouts.app')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-key me-2"></i>Change your password
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Use this form to update your account password. You must enter your current password to confirm your identity.</p>

                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                               autocomplete="current-password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password" required>
                        <small class="text-muted">At least 6 characters.</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm new password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control"
                               autocomplete="new-password" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update password
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
