@extends('layouts.app')

@section('title', 'New Purchase')
@section('page-title', 'New Purchase')

@section('content')
<form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
@csrf

<div class="row">
    {{-- Left: Items --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul me-2"></i>Purchase Items</span>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addItemBtn">
                    <i class="bi bi-plus-lg me-1"></i>Add Item
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width:200px">Product</th>
                                <th style="width:130px">Cost Price</th>
                                <th style="width:110px">Qty</th>
                                <th style="width:130px">Line Total</th>
                                <th style="width:50px"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            {{-- rows injected by JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
            @error('items')
                <div class="card-footer text-danger small"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Totals summary --}}
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted">Subtotal</td>
                                <td class="text-end fw-semibold" id="summarySubtotal">{{ $currency }}0.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label mb-0 text-muted">Discount</label>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ $currency }}</span>
                                        <input type="number" name="discount" id="discount" step="0.01" min="0" value="{{ old('discount', 0) }}"
                                               class="form-control text-end @error('discount') is-invalid @enderror">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label mb-0 text-muted">Tax</label>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ $currency }}</span>
                                        <input type="number" name="tax" id="tax" step="0.01" min="0" value="{{ old('tax', 0) }}"
                                               class="form-control text-end @error('tax') is-invalid @enderror">
                                    </div>
                                </td>
                            </tr>
                            <tr class="table-active fw-bold">
                                <td>Total</td>
                                <td class="text-end" id="summaryTotal">{{ $currency }}0.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label mb-0 text-muted">Paid Amount <span class="text-danger">*</span></label>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ $currency }}</span>
                                        <input type="number" name="paid_amount" id="paid_amount" step="0.01" min="0" value="{{ old('paid_amount', 0) }}"
                                               class="form-control text-end @error('paid_amount') is-invalid @enderror" required>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Details --}}
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Purchase Details</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" id="supplierSelect"
                            class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            @php $sDue = max(0, (float)$supplier->total_billed - (float)$supplier->total_paid); @endphp
                            <option value="{{ $supplier->id }}"
                                    data-due="{{ $sDue }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}{{ $supplier->company ? ' — ' . $supplier->company : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small><a href="{{ route('suppliers.create') }}" target="_blank" class="text-muted">+ Add new supplier</a></small>

                    {{-- Outstanding due alert shown when supplier has unpaid balance --}}
                    <div id="supplierDueAlert" class="alert alert-warning py-2 mt-2 small d-none">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        This supplier has an outstanding balance of
                        <strong id="supplierDueAmount"></strong>.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror"
                           value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                    @error('purchase_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method['code'] }}" {{ old('payment_method') === $method['code'] ? 'selected' : '' }}>
                                {{ $method['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="ordered"  {{ old('status', 'received') === 'ordered'  ? 'selected' : '' }}>Ordered (stock added later)</option>
                        <option value="received" {{ old('status', 'received') === 'received' ? 'selected' : '' }}>Received (add stock now)</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>Save Purchase
            </button>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
</form>

{{-- Product row template --}}
<template id="itemRowTemplate">
    <tr class="item-row">
        <td>
            <select name="items[__IDX__][product_id]" class="form-select form-select-sm product-select" required>
                <option value="">Select product…</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                            data-cost="{{ $product->cost_price }}"
                            data-name="{{ $product->name }}">
                        {{ $product->name }}{{ $product->company ? ' — ' . $product->company : '' }} ({{ $product->sku }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">{{ $currency }}</span>
                <input type="number" name="items[__IDX__][cost_price]" step="0.01" min="0"
                       class="form-control cost-price" placeholder="0.00" required>
            </div>
        </td>
        <td>
            <input type="number" name="items[__IDX__][quantity]" min="1" value="1"
                   class="form-control form-control-sm item-qty" required>
        </td>
        <td class="line-total fw-semibold text-end align-middle">{{ $currency }}0.00</td>
        <td class="align-middle">
            <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
const currencySymbol = '{{ $currency }}';
let itemIndex = 0;

function formatMoney(val) {
    return currencySymbol + parseFloat(val || 0).toFixed(2);
}

function recalculate() {
    let subtotal = 0;
    document.querySelectorAll('#itemsBody .item-row').forEach(row => {
        const qty   = parseFloat(row.querySelector('.item-qty').value) || 0;
        const cost  = parseFloat(row.querySelector('.cost-price').value) || 0;
        const line  = qty * cost;
        row.querySelector('.line-total').textContent = formatMoney(line);
        subtotal += line;
    });
    document.getElementById('summarySubtotal').textContent = formatMoney(subtotal);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax      = parseFloat(document.getElementById('tax').value) || 0;
    const total    = subtotal - discount + tax;
    document.getElementById('summaryTotal').textContent = formatMoney(total);
}

function addItemRow() {
    const tpl = document.getElementById('itemRowTemplate').content.cloneNode(true);
    const row = tpl.querySelector('tr');
    // Replace placeholder index
    row.innerHTML = row.innerHTML.replaceAll('__IDX__', itemIndex++);
    document.getElementById('itemsBody').appendChild(row);
    recalculate();
}

document.getElementById('addItemBtn').addEventListener('click', addItemRow);

document.getElementById('itemsBody').addEventListener('change', function (e) {
    if (e.target.classList.contains('product-select')) {
        const opt = e.target.selectedOptions[0];
        const costInput = e.target.closest('tr').querySelector('.cost-price');
        if (opt && opt.dataset.cost) {
            costInput.value = parseFloat(opt.dataset.cost).toFixed(2);
        }
        recalculate();
    }
    if (e.target.classList.contains('cost-price') || e.target.classList.contains('item-qty')) {
        recalculate();
    }
});

document.getElementById('itemsBody').addEventListener('input', function (e) {
    if (e.target.classList.contains('cost-price') || e.target.classList.contains('item-qty')) {
        recalculate();
    }
});

document.getElementById('itemsBody').addEventListener('click', function (e) {
    if (e.target.closest('.remove-item')) {
        e.target.closest('.item-row').remove();
        recalculate();
    }
});

document.getElementById('discount').addEventListener('input', recalculate);
document.getElementById('tax').addEventListener('input', recalculate);

// Start with one empty row
addItemRow();

// Show outstanding due alert when supplier is selected
(function () {
    const sel   = document.getElementById('supplierSelect');
    const alert = document.getElementById('supplierDueAlert');
    const amt   = document.getElementById('supplierDueAmount');
    if (!sel) return;

    function updateDue() {
        const opt = sel.selectedOptions[0];
        const due = parseFloat(opt ? opt.dataset.due : 0) || 0;
        if (due > 0) {
            amt.textContent = currencySymbol + due.toFixed(2);
            alert.classList.remove('d-none');
        } else {
            alert.classList.add('d-none');
        }
    }

    sel.addEventListener('change', updateDue);
    updateDue(); // run on page load in case old() re-selected a supplier
})();
</script>
@endpush
