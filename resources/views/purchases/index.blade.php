@extends('layouts.app')

@section('title', 'Purchases')
@section('page-title', 'Purchases')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Track all stock purchases from suppliers</p>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Purchase
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('purchases.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Reference # or supplier..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="supplier" class="form-select">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}{{ $supplier->company ? ' — ' . $supplier->company : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="ordered"  {{ request('status') === 'ordered'   ? 'selected' : '' }}>Ordered</option>
                    <option value="received" {{ request('status') === 'received'  ? 'selected' : '' }}>Received</option>
                    <option value="cancelled"{{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <th>Reference</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td><code>{{ $purchase->reference_number }}</code></td>
                            <td>
                                <div class="fw-semibold">{{ $purchase->supplier->name }}</div>
                                @if($purchase->supplier->company)
                                    <small class="text-muted">{{ $purchase->supplier->company }}</small>
                                @endif
                            </td>
                            <td class="text-muted">{{ $purchase->purchase_date->format('d M Y') }}</td>
                            <td class="text-muted">{{ $purchase->items_count ?? '—' }}</td>
                            <td class="fw-semibold">{{ $currency }}{{ number_format($purchase->total, 2) }}</td>
                            <td class="text-muted">{{ $currency }}{{ number_format($purchase->paid_amount, 2) }}</td>
                            <td>
                                @if($purchase->status === 'received')
                                    <span class="badge bg-success">Received</span>
                                @elseif($purchase->status === 'ordered')
                                    <span class="badge bg-warning text-dark">Ordered</span>
                                @else
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-bag-check fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No purchases found</p>
                                <a href="{{ route('purchases.create') }}" class="btn btn-primary mt-3">Record First Purchase</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($purchases->hasPages())
        <div class="card-footer">
            {{ $purchases->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
