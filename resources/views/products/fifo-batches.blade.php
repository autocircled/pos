@extends('layouts.app')

@section('title', 'FIFO Batches - ' . $product->name)
@section('page-title', 'FIFO Inventory Batches')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">{{ $product->name }}</h4>
                    <p class="text-muted mb-0">SKU: {{ $product->sku }} | Current Stock: {{ $product->quantity }}</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Products
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Total Available</h6>
                            <h3 class="text-primary">{{ $totalQuantity }}</h3>
                            <small class="text-muted">units in FIFO batches</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Average Cost</h6>
                            <h3 class="text-info">{{ $currency }}{{ number_format($averageCost, 2) }}</h3>
                            <small class="text-muted">per unit (FIFO weighted)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Current Cost</h6>
                            <h3 class="text-warning">{{ $currency }}{{ number_format($product->cost_price, 2) }}</h3>
                            <small class="text-muted">product record cost</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FIFO Batches Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-layers me-2"></i>FIFO Inventory Batches
                    </h5>
                </div>
                <div class="card-body">
                    @if($batches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Batch Date</th>
                                        <th>Initial Qty</th>
                                        <th>Remaining Qty</th>
                                        <th>Cost Price</th>
                                        <th>Total Value</th>
                                        <th>Source</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batches as $batch)
                                        <tr>
                                            <td>{{ $batch->batch_date->format('M d, Y') }}</td>
                                            <td>{{ $batch->quantity_initial }}</td>
                                            <td>
                                                <span class="badge {{ $batch->quantity_remaining > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $batch->quantity_remaining }}
                                                </span>
                                            </td>
                                            <td>{{ $currency }}{{ number_format($batch->cost_price, 2) }}</td>
                                            <td>{{ $currency }}{{ number_format($batch->cost_price * $batch->quantity_remaining, 2) }}</td>
                                            <td>
                                                @if($batch->purchaseItem)
                                                    <a href="{{ route('purchases.show', $batch->purchaseItem->purchase) }}" 
                                                       class="text-decoration-none">
                                                       Purchase #{{ $batch->purchaseItem->purchase->reference_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Initial Stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $batch->notes ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @if($batch->quantity_remaining > 0)
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-secondary">Exhausted</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $batches->firstItem() }} to {{ $batches->lastItem() }} 
                                of {{ $batches->total() }} batches
                            </div>
                            {{ $batches->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-layers fs-1 text-muted d-block mb-3"></i>
                            <h5>No FIFO Batches Found</h5>
                            <p class="text-muted">This product has no inventory batches tracked.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
