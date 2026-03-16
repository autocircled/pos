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
                    <i class="bi bi-globe me-2"></i>Timezone &amp; Locale
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Timezone <span class="text-danger">*</span></label>
                        <select name="timezone" class="form-select @error('timezone') is-invalid @enderror" required>
                            @php
                                $currentTz = old('timezone', $settings['timezone'] ?? 'Asia/Dhaka');
                                $grouped = collect(timezone_identifiers_list())->groupBy(function ($tz) {
                                    if (str_starts_with($tz, 'Asia/')) return 'Asia';
                                    if (str_starts_with($tz, 'America/')) return 'America';
                                    if (str_starts_with($tz, 'Europe/')) return 'Europe';
                                    if (str_starts_with($tz, 'Africa/')) return 'Africa';
                                    if (str_starts_with($tz, 'Australia/')) return 'Australia';
                                    if (in_array($tz, ['UTC', 'GMT'])) return 'UTC';
                                    return 'Other';
                                });
                            @endphp
                            <optgroup label="Asia (GMT+6 etc.)">
                                @foreach($grouped->get('Asia', []) as $tz)
                                    <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="UTC">
                                <option value="UTC" {{ $currentTz === 'UTC' ? 'selected' : '' }}>UTC</option>
                            </optgroup>
                            <optgroup label="Europe">
                                @foreach($grouped->get('Europe', []) as $tz)
                                    <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="America">
                                @foreach($grouped->get('America', []) as $tz)
                                    <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Other">
                                @foreach($grouped->get('Other', []) as $tz)
                                    <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <small class="text-muted">Used for all reports and dates. Default: Asia/Dhaka (GMT+6).</small>
                        @error('timezone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
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
                    <i class="bi bi-credit-card me-2"></i>Payment Methods
                </div>
                <div class="card-body">
                    <p class="text-muted small">These options will appear at checkout. Code is stored in the database (e.g. cash, card).</p>
                    <div id="paymentMethodsList">
                        @foreach($paymentMethods as $idx => $method)
                            @php
                                $code = is_array($method) ? ($method['code'] ?? '') : '';
                                $name = is_array($method) ? ($method['name'] ?? '') : '';
                            @endphp
                            <div class="row g-2 mb-2 payment-method-row">
                                <div class="col-5">
                                    <input type="text" name="payment_methods[code][]" class="form-control form-control-sm" 
                                           placeholder="Code (e.g. cash)" value="{{ $code }}">
                                </div>
                                <div class="col-5">
                                    <input type="text" name="payment_methods[name][]" class="form-control form-control-sm" 
                                           placeholder="Display name" value="{{ $name }}">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-payment-method" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addPaymentMethod">
                        <i class="bi bi-plus-lg me-1"></i>Add Payment Method
                    </button>
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

<template id="paymentMethodRowTemplate">
    <div class="row g-2 mb-2 payment-method-row">
        <div class="col-5">
            <input type="text" name="payment_methods[code][]" class="form-control form-control-sm" placeholder="Code (e.g. cash)">
        </div>
        <div class="col-5">
            <input type="text" name="payment_methods[name][]" class="form-control form-control-sm" placeholder="Display name">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-danger btn-sm remove-payment-method" title="Remove">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
document.getElementById('addPaymentMethod').addEventListener('click', function() {
    const tpl = document.getElementById('paymentMethodRowTemplate');
    const row = tpl.content.cloneNode(true);
    document.getElementById('paymentMethodsList').appendChild(row);
});

document.getElementById('paymentMethodsList').addEventListener('click', function(e) {
    if (e.target.closest('.remove-payment-method')) {
        e.target.closest('.payment-method-row').remove();
    }
});
</script>
@endpush
@endsection
