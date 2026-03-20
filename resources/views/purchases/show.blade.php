@extends('layouts.app')

@section('title', 'Purchase — ' . $purchase->reference_number)
@section('page-title', 'Purchase Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        {{-- Items table --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul me-2"></i>Items</span>
                <code>{{ $purchase->reference_number }}</code>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-end">Cost Price</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $i => $item)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->product_name }}</div>
                                    @if($item->product)
                                        <small class="text-muted">{{ $item->product->sku }}</small>
                                    @endif
                                </td>
                                <td class="text-end">{{ $currency }}{{ number_format($item->cost_price, 2) }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end fw-semibold">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end text-muted">Subtotal</td>
                                <td class="text-end">{{ $currency }}{{ number_format($purchase->subtotal, 2) }}</td>
                            </tr>
                            @if($purchase->discount > 0)
                            <tr>
                                <td colspan="4" class="text-end text-muted">Discount</td>
                                <td class="text-end text-danger">− {{ $currency }}{{ number_format($purchase->discount, 2) }}</td>
                            </tr>
                            @endif
                            @if($purchase->tax > 0)
                            <tr>
                                <td colspan="4" class="text-end text-muted">Tax</td>
                                <td class="text-end">+ {{ $currency }}{{ number_format($purchase->tax, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end">Total</td>
                                <td class="text-end fs-6">{{ $currency }}{{ number_format($purchase->total, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end text-muted">Paid</td>
                                <td class="text-end">{{ $currency }}{{ number_format($purchase->paid_amount, 2) }}</td>
                            </tr>
                            @php $due = $purchase->total - $purchase->paid_amount; @endphp
                            @if($due > 0)
                            <tr>
                                <td colspan="4" class="text-end text-danger">Due</td>
                                <td class="text-end text-danger fw-bold">{{ $currency }}{{ number_format($due, 2) }}</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Status card --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Details</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($purchase->status === 'received')
                                <span class="badge bg-success">Received</span>
                            @elseif($purchase->status === 'ordered')
                                <span class="badge bg-warning text-dark">Ordered</span>
                            @else
                                <span class="badge bg-secondary">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Supplier</td>
                        <td>
                            <div class="fw-semibold">{{ $purchase->supplier->name }}</div>
                            @if($purchase->supplier->company)
                                <small class="text-muted">{{ $purchase->supplier->company }}</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date</td>
                        <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment</td>
                        <td>{{ ucfirst($purchase->payment_method) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Recorded by</td>
                        <td>{{ $purchase->user->name }}</td>
                    </tr>
                    @if($purchase->notes)
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $purchase->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Supplier outstanding balance --}}
        @if($supplierTotalDue > 0)
        <div class="card mb-4 border-warning">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted">Total outstanding balance</div>
                        <div class="fw-semibold">{{ $purchase->supplier->name }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fs-5 fw-bold text-danger">{{ $currency }}{{ number_format($supplierTotalDue, 2) }}</div>
                        <small class="text-muted">across all orders</small>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('purchases.index', ['supplier' => $purchase->supplier_id, 'status' => 'received']) }}"
                       class="btn btn-outline-warning btn-sm w-100">
                        <i class="bi bi-list-ul me-1"></i>View All Dues for this Supplier
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        @if(! $purchase->isCancelled())
        @php $due = round($purchase->total - $purchase->paid_amount, 2); @endphp
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-gear me-2"></i>Actions</div>
            <div class="card-body d-grid gap-2">
                @if($purchase->isOrdered())
                    <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-pencil me-2"></i>Edit Purchase
                    </a>

                    <button type="button" class="btn btn-success w-100"
                            data-bs-toggle="modal" data-bs-target="#receiveModal">
                        <i class="bi bi-check-circle me-2"></i>Mark as Received
                    </button>
                @endif

                @if($purchase->isReceived() && $due > 0)
                    <button type="button" class="btn btn-warning w-100"
                            data-bs-toggle="modal" data-bs-target="#payModal">
                        <i class="bi bi-cash-coin me-2"></i>Record Payment
                        <span class="badge bg-dark ms-1">{{ $currency }}{{ number_format($due, 2) }} due</span>
                    </button>
                @endif

                @if($purchase->isReceived() && $due <= 0)
                    <div class="text-center text-success small py-1">
                        <i class="bi bi-check-circle-fill me-1"></i>Fully paid
                    </div>
                @endif

                <form action="{{ route('purchases.cancel', $purchase) }}" method="POST"
                      onsubmit="return confirm('Cancel this purchase?{{ $purchase->isReceived() ? " Stock will be reversed." : "" }}')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-2"></i>Cancel Purchase
                    </button>
                </form>
            </div>
        </div>
        @endif

        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary w-100">
            <i class="bi bi-arrow-left me-2"></i>Back to Purchases
        </a>
    </div>
</div>

{{-- Record Payment Modal (for received orders with outstanding due) --}}
@if($purchase->isReceived() && $due > 0)
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="payModalLabel">
                    <i class="bi bi-cash-coin text-warning me-2"></i>Record Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('purchases.pay', $purchase) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <table class="table table-sm table-borderless mb-3">
                        <tr>
                            <td class="text-muted">Purchase Total</td>
                            <td class="text-end fw-semibold">{{ $currency }}{{ number_format($purchase->total, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Paid</td>
                            <td class="text-end">{{ $currency }}{{ number_format($purchase->paid_amount, 2) }}</td>
                        </tr>
                        <tr class="text-danger">
                            <td class="fw-semibold">Outstanding Due</td>
                            <td class="text-end fw-bold">{{ $currency }}{{ number_format($due, 2) }}</td>
                        </tr>
                    </table>
                    <hr class="my-2">

                    <div class="mb-3">
                        <label class="form-label">Amount to Pay <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $currency }}</span>
                            <input type="number" name="payment_amount" id="payAmount"
                                   step="0.01" min="0.01" max="{{ $due }}"
                                   value="{{ $due }}" class="form-control" required>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="document.getElementById('payAmount').value='{{ $due }}'">
                                Pay Full ({{ $currency }}{{ number_format($due, 2) }})
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method['code'] }}"
                                    {{ $purchase->payment_method === $method['code'] ? 'selected' : '' }}>
                                    {{ $method['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-info py-2 small mb-0">
                        After this payment, remaining due:
                        <strong id="payRemaining">{{ $currency }}0.00</strong>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i>Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const dueAmt  = {{ $due }};
    const input   = document.getElementById('payAmount');
    const remaining = document.getElementById('payRemaining');
    const currency = '{{ $currency }}';
    if (!input) return;
    function update() {
        const pay  = Math.min(Math.max(parseFloat(input.value) || 0, 0), dueAmt);
        remaining.textContent = currency + Math.max(dueAmt - pay, 0).toFixed(2);
    }
    input.addEventListener('input', update);
    update();
})();
</script>
@endpush
@endif

{{-- Receive + Payment Modal --}}
@if($purchase->isOrdered())
@php $due = round($purchase->total - $purchase->paid_amount, 2); @endphp
<div class="modal fade" id="receiveModal" tabindex="-1" aria-labelledby="receiveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="receiveModalLabel">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Mark as Received
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('purchases.receive', $purchase) }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Payment summary --}}
                    <table class="table table-sm table-borderless mb-3">
                        <tr>
                            <td class="text-muted">Purchase Total</td>
                            <td class="text-end fw-semibold">{{ $currency }}{{ number_format($purchase->total, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Already Paid</td>
                            <td class="text-end">{{ $currency }}{{ number_format($purchase->paid_amount, 2) }}</td>
                        </tr>
                        <tr class="{{ $due > 0 ? 'text-danger' : 'text-success' }}">
                            <td class="fw-semibold">{{ $due > 0 ? 'Due Amount' : 'Fully Paid' }}</td>
                            <td class="text-end fw-bold">{{ $currency }}{{ number_format($due, 2) }}</td>
                        </tr>
                    </table>

                    @if($due > 0)
                        <hr class="my-2">
                        <p class="small text-muted mb-3">How much would you like to pay now?</p>

                        <div class="mb-3">
                            <label class="form-label">Pay Now</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ $currency }}</span>
                                <input type="number" name="payment_now" id="paymentNowInput"
                                       step="0.01" min="0" max="{{ $due }}"
                                       value="{{ $due }}"
                                       class="form-control">
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="document.getElementById('paymentNowInput').value = '{{ $due }}'">
                                    Pay Full ({{ $currency }}{{ number_format($due, 2) }})
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="document.getElementById('paymentNowInput').value = '0'">
                                    Pay Later
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method['code'] }}"
                                        {{ $purchase->payment_method === $method['code'] ? 'selected' : '' }}>
                                        {{ $method['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Live remaining due display --}}
                        <div class="alert alert-info py-2 mb-0 small" id="paymentSummary">
                            Remaining due after payment: <strong id="remainingDue">{{ $currency }}0.00</strong>
                        </div>
                    @else
                        <div class="alert alert-success py-2 mb-0">
                            <i class="bi bi-check-circle me-1"></i> This purchase is fully paid.
                        </div>
                    @endif

                    <div class="alert alert-warning py-2 mt-3 mb-0 small">
                        <i class="bi bi-box-seam me-1"></i>
                        Stock will be updated for <strong>{{ $purchase->items->count() }} item(s)</strong> once confirmed.
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Confirm &amp; Receive
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const due       = {{ $due }};
    const input     = document.getElementById('paymentNowInput');
    const remaining = document.getElementById('remainingDue');
    const currency  = '{{ $currency }}';

    if (!input) return;

    function update() {
        const pay  = Math.min(Math.max(parseFloat(input.value) || 0, 0), due);
        const left = Math.max(due - pay, 0);
        remaining.textContent = currency + left.toFixed(2);
    }

    input.addEventListener('input', update);
    update();
})();
</script>
@endpush
@endif
@endsection
