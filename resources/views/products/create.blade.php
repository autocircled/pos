@extends('layouts.app')

@section('title', 'Create Product')
@section('page-title', 'Create Product')

@section('content')
@php
    $fromDuplicate = isset($duplicateProduct);
    $d = $duplicateProduct ?? null;
@endphp
<div class="row">
    <div class="col-lg-8">
        @if($fromDuplicate)
            <div class="alert alert-info mb-3">
                <i class="bi bi-copy me-2"></i>Creating new product from <strong>{{ $d->name }}</strong>. SKU and stock are new; you can change any field.
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam me-2"></i>{{ $fromDuplicate ? 'New Product (from duplicate)' : 'New Product' }}
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $d->name ?? '') }}" placeholder="e.g., Blue Ballpoint Pen" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
                                   value="{{ old('company', $d->company ?? '') }}" placeholder="e.g., Matador">
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $d->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                   value="{{ old('sku', $sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" 
                                   value="{{ old('barcode', $fromDuplicate ? '' : '') }}" placeholder="Optional">
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Product description">{{ old('description', $d->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cost Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ $currency }}</span>
                                <input type="number" name="cost_price" step="0.01" min="0" 
                                       class="form-control @error('cost_price') is-invalid @enderror" 
                                       value="{{ old('cost_price', $d->cost_price ?? '') }}" placeholder="0.00" required>
                            </div>
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ $currency }}</span>
                                <input type="number" name="selling_price" step="0.01" min="0" 
                                       class="form-control @error('selling_price') is-invalid @enderror" 
                                       value="{{ old('selling_price', $d->selling_price ?? '') }}" placeholder="0.00" required>
                            </div>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Initial Stock <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" min="0" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   value="{{ old('quantity', $fromDuplicate ? 0 : 0) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Alert Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="alert_quantity" min="0" 
                                   class="form-control @error('alert_quantity') is-invalid @enderror" 
                                   value="{{ old('alert_quantity', $d->alert_quantity ?? 10) }}" required>
                            <small class="text-muted">Notify when stock falls below</small>
                            @error('alert_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                @php $unit = old('unit', $d->unit ?? 'piece'); @endphp
                                <option value="piece" {{ $unit == 'piece' ? 'selected' : '' }}>Piece</option>
                                <option value="pack" {{ $unit == 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="box" {{ $unit == 'box' ? 'selected' : '' }}>Box</option>
                                <option value="dozen" {{ $unit == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                <option value="ream" {{ $unit == 'ream' ? 'selected' : '' }}>Ream</option>
                                <option value="set" {{ $unit == 'set' ? 'selected' : '' }}>Set</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Max 2MB. Formats: JPEG, PNG, GIF{{ $fromDuplicate ? '. Duplicate does not copy image.' : '' }}</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                   {{ old('is_active', $d->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input type="hidden" name="requires_custom_price" value="0">
                            <input type="checkbox" name="requires_custom_price" class="form-check-input" id="requires_custom_price" 
                                   value="1" {{ old('requires_custom_price', $d->requires_custom_price ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="requires_custom_price">
                                <i class="bi bi-price-tag me-1"></i>Requires Custom Price
                                <small class="text-muted d-block">Will prompt for custom price in POS</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Create Product
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
