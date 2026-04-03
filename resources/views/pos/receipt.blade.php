@extends('layouts.app')

@section('title', 'Receipt - ' . $sale->invoice_number)
@section('page-title', 'Sale Receipt')

@push('styles')
<style>
    .receipt {
        max-width: 400px;
        margin: 0 auto;
        background: #fff;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .receipt-header {
        text-align: center;
        border-bottom: 2px dashed #e2e8f0;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    .receipt-header h3 {
        margin: 0;
        font-weight: 700;
    }
    .receipt-items {
        border-bottom: 2px dashed #e2e8f0;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    .receipt-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .receipt-item .qty {
        color: #64748b;
    }
    .receipt-totals {
        margin-bottom: 1rem;
    }
    .receipt-totals .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }
    .receipt-totals .total {
        font-size: 1.25rem;
        font-weight: 700;
        border-top: 1px solid #e2e8f0;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }
    .receipt-footer {
        text-align: center;
        color: #64748b;
        font-size: 0.85rem;
    }
    @media print {
        .no-print { display: none; }
        .receipt { box-shadow: none; }
    }
</style>
@endpush

@section('content')
<div class="mb-3 no-print">
    <a href="{{ route('pos.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-2"></i>New Sale
    </a>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-2"></i>Print Receipt
    </button>
    @if($sale->hasDue() && $sale->status !== 'cancelled')
        <a href="{{ route('pos.due-payments') }}" class="btn btn-success">
            <i class="bi bi-cash-stack me-2"></i>Due Payments
        </a>
    @endif
</div>

<div class="receipt">
    <div class="receipt-header">
        <h3><i class="bi bi-pencil-square me-2"></i>Stationery POS</h3>
        <p class="mb-0 text-muted">Invoice: {{ $sale->invoice_number }}</p>
        <small>{{ $sale->created_at->format('d M Y, h:i A') }}</small>
        @if($sale->status === 'cancelled')
            <div class="badge bg-danger mt-2">CANCELLED</div>
        @endif
    </div>
    
    @if($sale->customer_name)
        <div class="mb-3">
            <strong>Customer:</strong> {{ $sale->customer_name }}<br>
            @if($sale->customer_phone)
                <strong>Phone:</strong> {{ $sale->customer_phone }}
            @endif
        </div>
    @endif
    
    <div class="receipt-items">
        @foreach($sale->items as $item)
            <div class="receipt-item">
                <div>
                    <div>
                        {{ $item->product_name }}
                        @if($item->hasCustomPrice())
                            <span class="badge bg-info text-white ms-1" style="font-size: 0.7em;">Custom</span>
                        @endif
                    </div>
                    <div class="qty">
                        {{ $item->quantity }} × {{ $currency }}{{ number_format($item->getActualPrice(), 2) }}
                        @if($item->hasCustomPrice())
                            <small class="text-muted d-block">(Original: {{ $currency }}{{ number_format($item->unit_price, 2) }})</small>
                        @endif
                    </div>
                </div>
                <div class="fw-semibold">{{ $currency }}{{ number_format($item->total, 2) }}</div>
            </div>
        @endforeach
    </div>
    
    <div class="receipt-totals">
        <div class="row">
            <span>Subtotal</span>
            <span>{{ $currency }}{{ number_format($sale->subtotal, 2) }}</span>
        </div>
        @if($sale->discount > 0)
            <div class="row text-danger">
                <span>Discount</span>
                <span>-{{ $currency }}{{ number_format($sale->discount, 2) }}</span>
            </div>
        @endif
        @if($sale->tax > 0)
            <div class="row">
                <span>Tax</span>
                <span>{{ $currency }}{{ number_format($sale->tax, 2) }}</span>
            </div>
        @endif
        <div class="row total">
            <span>Total</span>
            <span>{{ $currency }}{{ number_format($sale->total, 2) }}</span>
        </div>
        
        <!-- Payment Status -->
        <div class="row {{ $sale->payment_status === 'paid' ? 'text-success' : ($sale->payment_status === 'partial' ? 'text-warning' : 'text-danger') }}">
            <span>Payment Status</span>
            <span class="fw-semibold">{{ ucfirst($sale->payment_status) }}</span>
        </div>
        
        <div class="row">
            <span>Initial Payment ({{ ucfirst($sale->payment_method) }})</span>
            <span>{{ $currency }}{{ number_format($sale->paid_amount, 2) }}</span>
        </div>
        
        @if($sale->change_amount > 0)
            <div class="row">
                <span>Change</span>
                <span>{{ $currency }}{{ number_format($sale->change_amount, 2) }}</span>
            </div>
        @endif
        
        @if($sale->due_amount > 0)
            <div class="row text-danger">
                <span>Original Due</span>
                <span>{{ $currency }}{{ number_format($sale->due_amount, 2) }}</span>
            </div>
        @endif
        
        @if($sale->duePayments->count() > 0)
            <div class="row text-success">
                <span>Additional Payments</span>
                <span>{{ $currency }}{{ number_format($sale->duePayments->sum('amount'), 2) }}</span>
            </div>
        @endif
        
        @if($sale->hasDue())
            <div class="row text-danger fw-bold">
                <span>Remaining Due</span>
                <span>{{ $currency }}{{ number_format($sale->getRemainingDue(), 2) }}</span>
            </div>
        @endif
        
        <div class="row total">
            <span>Total Paid</span>
            <span>{{ $currency }}{{ number_format($sale->getTotalPaidAmount(), 2) }}</span>
        </div>
    </div>
    
    <!-- Due Payment History -->
    @if($sale->duePayments->count() > 0)
        <div class="mb-3">
            <h6 class="border-bottom pb-2">Payment History</h6>
            @foreach($sale->duePayments as $payment)
                <div class="receipt-item">
                    <div>
                        <div>{{ ucfirst($payment->payment_method) }} Payment</div>
                        <div class="qty">{{ $payment->created_at->format('d M Y, h:i A') }}</div>
                        @if($payment->notes)
                            <div class="text-muted small">{{ $payment->notes }}</div>
                        @endif
                    </div>
                    <div class="fw-semibold text-success">{{ $currency }}{{ number_format($payment->amount, 2) }}</div>
                </div>
            @endforeach
        </div>
    @endif
    
    <div class="receipt-footer">
        <p class="mb-1">Served by: {{ $sale->user->name }}</p>
        @if($sale->notes)
            <p class="mb-1"><small>Notes: {{ $sale->notes }}</small></p>
        @endif
        <p class="mb-0">Thank you for shopping with us!</p>
    </div>
</div>
@endsection
