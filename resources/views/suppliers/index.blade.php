@extends('layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Suppliers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage your product suppliers</p>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Supplier
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('suppliers.index') }}" method="GET" class="row g-3">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, company, email or phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Outstanding Due</th>
                        <th>Status</th>
                        <th width="130">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="fw-semibold">{{ $supplier->name }}</td>
                            <td class="text-muted">{{ $supplier->company ?? '—' }}</td>
                            <td class="text-muted">{{ $supplier->email ?? '—' }}</td>
                            <td class="text-muted">{{ $supplier->phone ?? '—' }}</td>
                            <td>
                                @php $due = max(0, (float)$supplier->total_billed - (float)$supplier->total_paid); @endphp
                                @if($due > 0)
                                    <span class="fw-semibold text-danger">{{ $currency }}{{ number_format($due, 2) }}</span>
                                @else
                                    <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Clear</span>
                                @endif
                            </td>
                            <td>
                                @if($supplier->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this supplier? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-truck fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No suppliers found</p>
                                <a href="{{ route('suppliers.create') }}" class="btn btn-primary mt-3">Add First Supplier</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($suppliers->hasPages())
        <div class="card-footer">
            {{ $suppliers->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
