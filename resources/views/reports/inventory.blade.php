@extends('layouts.app')

@section('title', 'Inventory Report')
@section('page-title', 'Inventory Report')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $summary['total_products'] }}</h3>
                <p>Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $currency }}{{ number_format($summary['total_stock_value'], 2) }}</h3>
                <p>Stock Value (Cost)</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $summary['low_stock_count'] }}</h3>
                <p>Low Stock Items</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $summary['out_of_stock_count'] }}</h3>
                <p>Out of Stock</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-folder me-2"></i>Stock by Category
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($categoryStock as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $category->name }}</div>
                                <small class="text-muted">{{ $category->products_count }} products</small>
                            </div>
                            <span class="badge bg-primary">{{ $category->products_sum_quantity ?? 0 }} units</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul me-2"></i>All Products Inventory
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-hover mb-0">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>SKU</th>
                                <th class="text-end">Stock</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="fw-semibold">{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td><code>{{ $product->sku }}</code></td>
                                    <td class="text-end">{{ $product->quantity }} {{ $product->unit }}</td>
                                    <td class="text-end">{{ $currency }}{{ number_format($product->cost_price, 2) }}</td>
                                    <td class="text-end fw-semibold">{{ $currency }}{{ number_format($product->quantity * $product->cost_price, 2) }}</td>
                                    <td>
                                        @if($product->quantity == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->isLowStock())
                                            <span class="badge bg-warning">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
