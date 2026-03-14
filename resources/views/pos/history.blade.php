@extends('layouts.app')

@section('title', 'Sales History')
@section('page-title', 'Sales History')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('pos.history') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('pos.history') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>
                                <a href="{{ route('pos.receipt', $sale) }}" class="fw-semibold">
                                    {{ $sale->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $sale->customer_name ?: 'Walk-in' }}</td>
                            <td>{{ $sale->items->count() }} items</td>
                            <td class="fw-semibold">{{ $currency }}{{ number_format($sale->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $sale->payment_method === 'cash' ? 'success' : ($sale->payment_method === 'card' ? 'primary' : 'info') }}">
                                    {{ ucfirst($sale->payment_method) }}
                                </span>
                            </td>
                            <td>
                                @if($sale->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($sale->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pos.receipt', $sale) }}" class="btn btn-outline-primary" title="View Receipt">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    @if($sale->status === 'completed')
                                        <form action="{{ route('pos.cancel', $sale) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to cancel this sale? Stock will be restored.')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger" title="Cancel Sale">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-receipt-cutoff fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No sales found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sales->hasPages())
        <div class="card-footer">
            {{ $sales->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
