@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Manage your stationery inventory</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Product
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('products.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <input type="text" name="company" class="form-control" placeholder="Company..." value="{{ request('company') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock" class="form-select">
                    <option value="">All Stock Levels</option>
                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-1">
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
                        <th>Product</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Cost</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                             class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-box text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        @if($product->company)
                                            <small class="text-muted d-block">{{ $product->company }}</small>
                                        @endif
                                        @if($product->barcode)
                                            <small class="text-muted">{{ $product->barcode }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>{{ $currency }}{{ number_format($product->cost_price, 2) }}</td>
                            <td class="fw-semibold">{{ $currency }}{{ number_format($product->selling_price, 2) }}</td>
                            <td>
                                @if($product->quantity == 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($product->isLowStock())
                                    <span class="badge bg-warning">{{ $product->quantity }} {{ $product->unit }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->quantity }} {{ $product->unit }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('products.duplicate', $product) }}" class="btn btn-outline-secondary" title="Duplicate">
                                        <i class="bi bi-copy"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-box-seam fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No products found</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">Add First Product</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
        <div class="card-footer">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
