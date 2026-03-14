

<?php $__env->startSection('title', 'Profit Report'); ?>
<?php $__env->startSection('page-title', 'Profit Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="card mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('reports.profit')); ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo e($startDate); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo e($endDate); ?>" max="<?php echo e(date('Y-m-d')); ?>">
            </div>
            <div class="col-md-3">
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
                <h3><?php echo e($currency); ?><?php echo e(number_format($totalRevenue, 2)); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-cart-dash"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e($currency); ?><?php echo e(number_format($totalCost, 2)); ?></h3>
                <p>Total Cost</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e($currency); ?><?php echo e(number_format($totalProfit, 2)); ?></h3>
                <p>Net Profit</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-percent"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e(number_format($profitMargin, 1)); ?>%</h3>
                <p>Profit Margin</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Daily Profit Trend
            </div>
            <div class="card-body">
                <canvas id="profitChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Summary
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <small class="text-muted d-block">Revenue vs Cost</small>
                    <div class="progress mt-2" style="height: 24px;">
                        <div class="progress-bar bg-success" style="width: <?php echo e($totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0); ?>%">
                            Profit
                        </div>
                        <div class="progress-bar bg-secondary" style="width: <?php echo e($totalRevenue > 0 ? ($totalCost / $totalRevenue) * 100 : 0); ?>%">
                            Cost
                        </div>
                    </div>
                </div>
                
                <div class="text-center p-3 bg-light rounded">
                    <h5 class="text-muted mb-2">For every <?php echo e($currency); ?>100 revenue</h5>
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-success mb-0"><?php echo e($currency); ?><?php echo e(number_format($profitMargin, 0)); ?></h4>
                            <small class="text-muted">Profit</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-secondary mb-0"><?php echo e($currency); ?><?php echo e(number_format(100 - $profitMargin, 0)); ?></h4>
                            <small class="text-muted">Cost</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-trophy me-2"></i>Most Profitable Products
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Qty Sold</th>
                        <th class="text-end">Revenue</th>
                        <th class="text-end">Profit</th>
                        <th class="text-end">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $productProfit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($product->product_name); ?></td>
                            <td class="text-end"><?php echo e($product->total_qty); ?></td>
                            <td class="text-end"><?php echo e($currency); ?><?php echo e(number_format($product->revenue, 2)); ?></td>
                            <td class="text-end text-success fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($product->profit, 2)); ?></td>
                            <td class="text-end">
                                <span class="badge bg-<?php echo e($product->revenue > 0 ? (($product->profit / $product->revenue) * 100 > 20 ? 'success' : 'warning') : 'secondary'); ?>">
                                    <?php echo e($product->revenue > 0 ? number_format(($product->profit / $product->revenue) * 100, 1) : 0); ?>%
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No sales data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const cs = window.currencySymbol;
    const dailyProfit = <?php echo json_encode($dailyProfit, 15, 512) ?>;
    const labels = Object.keys(dailyProfit);
    const profitData = labels.map(date => dailyProfit[date].profit);
    const revenueData = labels.map(date => dailyProfit[date].revenue);
    
    new Chart(document.getElementById('profitChart'), {
        type: 'bar',
        data: {
            labels: labels.map(date => new Date(date).toLocaleDateString('en-US', { day: 'numeric', month: 'short' })),
            datasets: [
                {
                    label: 'Revenue',
                    data: revenueData,
                    backgroundColor: 'rgba(79, 70, 229, 0.3)',
                    borderColor: '#4f46e5',
                    borderWidth: 1
                },
                {
                    label: 'Profit',
                    data: profitData,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: '#10b981',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => cs + value.toLocaleString() }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/reports/profit.blade.php ENDPATH**/ ?>