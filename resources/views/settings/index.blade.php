@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-currency-exchange me-2"></i>Currency Settings
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                   value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required>
                            <small class="text-muted">e.g., ৳, $, €, ₹, £</small>
                            @error('currency_symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" name="currency_code" class="form-control @error('currency_code') is-invalid @enderror" 
                                   value="{{ old('currency_code', $settings['currency_code']) }}" required>
                            <small class="text-muted">e.g., BDT, USD, EUR, INR, GBP</small>
                            @error('currency_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Default Tax Percentage <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="tax_percentage" step="0.01" min="0" max="100"
                                       class="form-control @error('tax_percentage') is-invalid @enderror" 
                                       value="{{ old('tax_percentage', $settings['tax_percentage']) }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('tax_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-shop me-2"></i>Shop Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Shop Name <span class="text-danger">*</span></label>
                        <input type="text" name="shop_name" class="form-control @error('shop_name') is-invalid @enderror" 
                               value="{{ old('shop_name', $settings['shop_name']) }}" required>
                        @error('shop_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shop Address</label>
                        <textarea name="shop_address" class="form-control @error('shop_address') is-invalid @enderror" 
                                  rows="2">{{ old('shop_address', $settings['shop_address']) }}</textarea>
                        @error('shop_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shop Phone</label>
                        <input type="text" name="shop_phone" class="form-control @error('shop_phone') is-invalid @enderror" 
                               value="{{ old('shop_phone', $settings['shop_phone']) }}">
                        @error('shop_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
