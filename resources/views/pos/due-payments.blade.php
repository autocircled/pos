@extends('layouts.app')

@section('title', 'Due Payments')
@section('page-title', 'Due Payments Management')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('pos.due-payments') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" placeholder="Invoice, Customer, Phone...">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Search</button>
                <a href="{{ route('pos.due-payments') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                            <td>
                                <div>
                                    {{ $sale->customer_name ?: 'Walk-in' }}
                                    @if($sale->customer_phone)
                                        <br><small class="text-muted">{{ $sale->customer_phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="fw-semibold">{{ $currency }}{{ number_format($sale->total, 2) }}</td>
                            <td>
                                <div>
                                    {{ $currency }}{{ number_format($sale->getTotalPaidAmount(), 2) }}
                                    @if($sale->duePayments->count() > 0)
                                        <br><small class="text-muted">{{ $sale->duePayments->count() }} payments</small>
                                    @endif
                                </div>
                            </td>
                            <td class="fw-bold text-danger">
                                {{ $currency }}{{ number_format($sale->getRemainingDue(), 2) }}
                            </td>
                            <td>
                                {!! $sale->payment_status_badge !!}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pos.receipt', $sale) }}" class="btn btn-outline-primary" title="View Receipt">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    @if($sale->getRemainingDue() > 0)
                                        <button type="button" class="btn btn-outline-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#paymentModal{{ $sale->id }}"
                                                title="Add Payment">
                                            <i class="bi bi-cash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-cash-stack fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No due payments found</p>
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

<!-- Payment Modals -->
@foreach($sales as $sale)
    @if($sale->getRemainingDue() > 0)
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
                                           min="1" max="{{ $sale->getRemainingDue() }}" step="1" required>
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
