@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $currency }}{{ number_format($todaySales, 2) }}</h3>
                <p>Today's Sales</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $currency }}{{ number_format($monthSales, 2) }}</h3>
                <p>Monthly Sales</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $todayTransactions }}</h3>
                <p>Today's Transactions</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon {{ $lowStockProducts > 0 ? 'red' : 'yellow' }}">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $lowStockProducts }}</h3>
                <p>Low Stock Items</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart me-2"></i>Sales Last 7 Days</span>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Top Selling Products
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($topSellingProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $product->product_name }}</span>
                            <span class="badge bg-primary rounded-pill">{{ $product->total_sold }} sold</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">No sales this month</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Recent Sales</span>
                <a href="{{ route('pos.history') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td>
                                        <a href="{{ route('pos.receipt', $sale) }}">{{ $sale->invoice_number }}</a>
                                    </td>
                                    <td>{{ $sale->customer_name ?: 'Walk-in Customer' }}</td>
                                    <td class="fw-semibold">{{ $currency }}{{ number_format($sale->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $sale->payment_method === 'cash' ? 'success' : ($sale->payment_method === 'card' ? 'primary' : 'info') }}">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td>{{ $sale->created_at->format('d M, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No recent sales</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle text-warning me-2"></i>Low Stock Alert</span>
                <a href="{{ route('products.index', ['stock' => 'low']) }}" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($lowStockItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $item->name }}</div>
                                <small class="text-muted">{{ $item->category->name }}</small>
                            </div>
                            <span class="badge bg-{{ $item->quantity == 0 ? 'danger' : 'warning' }}">
                                {{ $item->quantity }} left
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-check-circle text-success fs-4 d-block mb-2"></i>
                            All products in stock
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesData = @json($salesChart);
    const ctx = document.getElementById('salesChart').getContext('2d');
    const cs = window.currencySymbol;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
            datasets: [{
                label: 'Sales',
                data: salesData.map(item => item.total),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4f46e5',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => cs + value.toLocaleString()
                    }
                }
            }
        }
    });
</script>
@endpush
