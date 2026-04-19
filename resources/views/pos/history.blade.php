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
                        <th>Profit</th>
                        <th>Payment Status</th>
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
                            <td class="fw-semibold">{{ $currency }}{{ number_format($sale->getProfit(), 2) }}</td>
                            <td>
                                {!! $sale->payment_status_badge !!}
                                @if($sale->getRemainingDue())
                                    <br><small class="text-danger">Due: {{ $currency }}{{ number_format($sale->getRemainingDue(), 2) }}</small>
                                @endif
                            </td>
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
                                        <a href="{{ route('pos.edit', $sale) }}" class="btn btn-outline-warning" title="Edit Sale">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($sale->getRemainingDue())
                                            <button type="button" class="btn btn-outline-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal{{ $sale->id }}"
                                                    title="Add Payment">
                                                <i class="bi bi-cash"></i>
                                            </button>
                                        @endif
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

@foreach($sales as $sale)
    @if($sale->hasDue())
        <div class="modal fade" id="paymentModal{{ $sale->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payment - {{ $sale->invoice_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('pos.add-due-payment', $sale) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small>Total Amount</small><br>
                                        <strong>{{ $currency }}{{ number_format($sale->total, 2) }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <small>Remaining Due</small><br>
                                        <strong class="text-danger">{{ $currency }}{{ number_format($sale->getRemainingDue(), 2) }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <small>Total Paid</small><br>
                                        <strong class="text-success">{{ $currency }}{{ number_format($sale->getTotalPaidAmount(), 2) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" name="amount" class="form-control" 
                                           value="{{ $sale->getRemainingDue() }}" 
                                           min="0.01" max="{{ $sale->getRemainingDue() }}" step="0.01" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Select payment method</option>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" 
                                          placeholder="Optional payment notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg me-2"></i>Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
