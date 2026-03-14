

<?php $__env->startSection('title', 'Daily Report'); ?>
<?php $__env->startSection('page-title', 'Daily Sales Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="card mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('reports.daily')); ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Select Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>" max="<?php echo e(date('Y-m-d')); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e($currency); ?><?php echo e(number_format($summary['total_sales'], 2)); ?></h3>
                <p>Total Sales</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e($currency); ?><?php echo e(number_format($summary['total_profit'], 2)); ?></h3>
                <p>Total Profit</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e($summary['total_transactions']); ?></h3>
                <p>Transactions</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e($summary['total_items_sold']); ?></h3>
                <p>Items Sold</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-credit-card me-2"></i>Payment Methods
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Cash</span>
                        <span class="fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($summary['cash_sales'], 2)); ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: <?php echo e($summary['total_sales'] > 0 ? ($summary['cash_sales'] / $summary['total_sales']) * 100 : 0); ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Card</span>
                        <span class="fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($summary['card_sales'], 2)); ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: <?php echo e($summary['total_sales'] > 0 ? ($summary['card_sales'] / $summary['total_sales']) * 100 : 0); ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>UPI</span>
                        <span class="fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($summary['upi_sales'], 2)); ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: <?php echo e($summary['total_sales'] > 0 ? ($summary['upi_sales'] / $summary['total_sales']) * 100 : 0); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-clock me-2"></i>Hourly Sales
            </div>
            <div class="card-body">
                <canvas id="hourlyChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Top Selling Products
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($product->product_name); ?></td>
                                    <td class="text-end"><?php echo e($product->total_qty); ?></td>
                                    <td class="text-end fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($product->total_amount, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No sales data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul me-2"></i>All Transactions
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Time</th>
                                <th>Payment</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('pos.receipt', $sale)); ?>"><?php echo e($sale->invoice_number); ?></a>
                                    </td>
                                    <td><?php echo e($sale->created_at->format('h:i A')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($sale->payment_method === 'cash' ? 'success' : ($sale->payment_method === 'card' ? 'primary' : 'info')); ?>">
                                            <?php echo e(ucfirst($sale->payment_method)); ?>

                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($sale->total, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No transactions</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const cs = window.currencySymbol;
    const hourlyData = <?php echo json_encode($hourlySales, 15, 512) ?>;
    const hours = Array.from({length: 24}, (_, i) => i);
    
    const salesByHour = hours.map(hour => {
        const found = hourlyData.find(item => item.hour === hour);
        return found ? found.total : 0;
    });
    
    new Chart(document.getElementById('hourlyChart'), {
        type: 'bar',
        data: {
            labels: hours.map(h => h.toString().padStart(2, '0') + ':00'),
            datasets: [{
                label: 'Sales',
                data: salesByHour,
                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => cs + value }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/reports/daily.blade.php ENDPATH**/ ?>