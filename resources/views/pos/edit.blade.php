@extends('layouts.app')

@section('title', 'Edit Sale')
@section('page-title', 'Edit Sale')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Sale - {{ $sale->invoice_number }}</h5>
                <a href="{{ route('pos.history') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to History
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('pos.update', $sale) }}" method="POST" id="editSaleForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" 
                               value="{{ $sale->customer_name }}" placeholder="Optional">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Customer Phone</label>
                        <input type="text" name="customer_phone" class="form-control" 
                               value="{{ $sale->customer_phone }}" placeholder="Optional">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Add Products</label>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <select id="productSelect" class="form-select">
                                    <option value="">Select a product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->selling_price }}"
                                                data-stock="{{ $product->quantity }}"
                                                data-name="{{ $product->name }}">
                                            {{ $product->name }} (Stock: {{ $product->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" id="quantityInput" class="form-control" 
                                       placeholder="Qty" min="1" value="1">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary w-100" onclick="addProduct()">
                                    <i class="bi bi-plus-lg"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sale Items</label>
                        <div id="saleItems" class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->items as $index => $item)
                                        <tr data-index="{{ $index }}">
                                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][quantity]" 
                                                       class="form-control form-control-sm" value="{{ $item->quantity }}" 
                                                       min="1" onchange="calculateTotals()">
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][discount]" 
                                                       class="form-control form-control-sm" value="{{ $item->discount }}" 
                                                       min="0" step="0.01" onchange="calculateTotals()">
                                            </td>
                                            <td class="item-total">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Discount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" name="discount" class="form-control" 
                                           value="{{ $sale->discount }}" min="0" step="1" onchange="calculateTotals()">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tax</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" name="tax" class="form-control" 
                                           value="{{ $sale->tax }}" min="0" step="1" onchange="calculateTotals()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <label class="form-label">Total Amount</label>
                            <input type="text" id="modalTotal" class="form-control fw-bold" readonly>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Paid Amount</label>
                            <input type="number" name="paid_amount" class="form-control" 
                                   value="{{ $sale->paid_amount }}" min="0" step="0.01" onchange="calculateTotals()">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Due Amount</label>
                            <input type="text" id="dueAmountDisplay" class="form-control fw-bold text-danger" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <div class="btn-group w-100 flex-wrap" role="group">
                            @foreach($paymentMethods as $idx => $method)
                                <input type="radio" class="btn-check" name="payment_method" 
                                       id="payment{{ $method['code'] }}" value="{{ $method['code'] }}" 
                                       {{ $sale->payment_method === $method['code'] ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="payment{{ $method['code'] }}">
                                    {{ $method['name'] }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $sale->notes }}</textarea>
                    </div>

                    <div class="alert alert-info">
                        <div class="row text-center">
                            <div class="col-3">
                                <strong>Subtotal:</strong><br>
                                <span id="subtotal">{{ $currency }}{{ number_format($sale->subtotal, 2) }}</span>
                            </div>
                            <div class="col-3">
                                <strong>Discount:</strong><br>
                                <span id="totalDiscount">{{ $currency }}{{ number_format($sale->discount, 2) }}</span>
                            </div>
                            <div class="col-3">
                                <strong>Tax:</strong><br>
                                <span id="totalTax">{{ $currency }}{{ number_format($sale->tax, 2) }}</span>
                            </div>
                            <div class="col-3">
                                <strong>Total:</strong><br>
                                <span id="grandTotal" class="fs-5 text-primary">{{ $currency }}{{ number_format($sale->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update Sale
                        </button>
                        <a href="{{ route('pos.history') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Original Sale Details</h6>
            </div>
            <div class="card-body">
                <p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $sale->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Created by:</strong> {{ $sale->user->name }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-success">{{ ucfirst($sale->status) }}</span>
                </p>
                <hr>
                <h6>Original Items:</h6>
                @foreach($sale->items as $item)
                    <div class="d-flex justify-content-between small">
                        <span>{{ $item->product_name }} (x{{ $item->quantity }})</span>
                        <span>{{ $currency }}{{ number_format($item->total, 2) }}</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Original Total:</span>
                    <span>{{ $currency }}{{ number_format($sale->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const currencySymbol = '{{ $currency }}';
let itemIndex = {{ $sale->items->count() }};

function addProduct() {
    const select = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    
    if (!select.value || !quantityInput.value) {
        alert('Please select a product and quantity');
        return;
    }
    
    const option = select.options[select.selectedIndex];
    const stock = parseInt(option.dataset.stock);
    const quantity = parseInt(quantityInput.value);
    
    if (quantity > stock) {
        alert('Insufficient stock. Available: ' + stock);
        return;
    }
    
    const tbody = document.querySelector('#saleItems tbody');
    const row = document.createElement('tr');
    row.dataset.index = itemIndex;
    
    const price = parseFloat(option.dataset.price);
    const total = price * quantity;
    
    row.innerHTML = 
        '<input type="hidden" name="items[' + itemIndex + '][product_id]" value="' + select.value + '">' +
        '<td>' + option.dataset.name + '</td>' +
        '<td>' + currencySymbol + price.toFixed(2) + '</td>' +
        '<td>' +
            '<input type="number" name="items[' + itemIndex + '][quantity]" ' +
                   'class="form-control form-control-sm" value="' + quantity + '" ' +
                   'min="1" onchange="calculateTotals()">' +
        '</td>' +
        '<td>' +
            '<input type="number" name="items[' + itemIndex + '][discount]" ' +
                   'class="form-control form-control-sm" value="0" ' +
                   'min="0" step="0.01" onchange="calculateTotals()">' +
        '</td>' +
        '<td class="item-total">' + currencySymbol + total.toFixed(2) + '</td>' +
        '<td>' +
            '<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">' +
                '<i class="bi bi-trash"></i>' +
            '</button>' +
        '</td>';
    
    tbody.appendChild(row);
    itemIndex++;
    
    select.value = '';
    quantityInput.value = '1';
    calculateTotals();
}

function removeItem(button) {
    button.closest('tr').remove();
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('#saleItems tbody tr').forEach(row => {
        const quantity = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
        const priceText = row.cells[1].textContent.replace(currencySymbol, '');
        const price = parseFloat(priceText);
        const discount = parseFloat(row.querySelector('input[name*="discount"]').value) || 0;
        const total = (price * quantity) - discount;
        
        row.querySelector('.item-total').textContent = currencySymbol + total.toFixed(2);
        subtotal += total;
    });
    
    const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
    const tax = parseFloat(document.querySelector('input[name="tax"]').value) || 0;
    const total = subtotal - discount + tax;
    const paidAmount = parseFloat(document.querySelector('input[name="paid_amount"]').value) || 0;
    const dueAmount = Math.max(0, total - paidAmount);
    
    document.getElementById('subtotal').textContent = currencySymbol + subtotal.toFixed(2);
    document.getElementById('totalDiscount').textContent = currencySymbol + discount.toFixed(2);
    document.getElementById('totalTax').textContent = currencySymbol + tax.toFixed(2);
    document.getElementById('grandTotal').textContent = currencySymbol + total.toFixed(2);
    document.getElementById('modalTotal').value = currencySymbol + total.toFixed(2);
    document.getElementById('dueAmountDisplay').value = currencySymbol + dueAmount.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endsection
