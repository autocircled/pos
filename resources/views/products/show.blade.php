@extends('layouts.app')

@section('title', $product->name)
@section('page-title', 'Product Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box-seam me-2"></i>Product Information</span>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 200px; height: 200px;">
                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-1">{{ $product->name }}</h4>
                        <p class="text-muted mb-3">
                            <span class="badge bg-primary">{{ $product->category->name }}</span>
                            @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">SKU</small>
                                <code>{{ $product->sku }}</code>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Barcode</small>
                                <span>{{ $product->barcode ?: '-' }}</span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-4">
                                <small class="text-muted d-block">Cost Price</small>
                                <span>{{ $currency }}{{ number_format($product->cost_price, 2) }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Selling Price</small>
                                <span class="fw-bold text-success">{{ $currency }}{{ number_format($product->selling_price, 2) }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Profit Margin</small>
                                <span>{{ $currency }}{{ number_format($product->getProfit(), 2) }}</span>
                            </div>
                        </div>
                        
                        @if($product->description)
                            <p class="text-muted">{{ $product->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Recent Sales
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->saleItems()->with('sale')->latest()->take(10)->get() as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('pos.receipt', $item->sale) }}">{{ $item->sale->invoice_number }}</a>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="fw-semibold">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                                    <td>{{ $item->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No sales recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-box me-2"></i>Stock Information
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h2 class="mb-0 {{ $product->isLowStock() ? 'text-warning' : 'text-success' }}">
                        {{ $product->quantity }}
                    </h2>
                    <small class="text-muted">{{ ucfirst($product->unit) }}s in stock</small>
                </div>
                
                @if($product->isLowStock())
                    <div class="alert alert-warning py-2 mb-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Low stock alert (below {{ $product->alert_quantity }})
                    </div>
                @endif
                
                <hr>
                
                <h6 class="mb-3">Adjust Stock</h6>
                <form action="{{ route('products.adjust-stock', $product) }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="number" name="adjustment" class="form-control" min="1" value="1" required>
                        <select name="type" class="form-select" style="max-width: 120px;">
                            <option value="add">Add</option>
                            <option value="subtract">Subtract</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-arrow-repeat me-2"></i>Update Stock
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Details
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Created</span>
                    <span>{{ $product->created_at->format('d M Y') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Last Updated</span>
                    <span>{{ $product->updated_at->format('d M Y') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Total Sold</span>
                    <span>{{ $product->saleItems()->sum('quantity') }} {{ $product->unit }}s</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
