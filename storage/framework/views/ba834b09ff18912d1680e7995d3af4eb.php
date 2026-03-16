

<?php $__env->startSection('title', 'Inventory Report'); ?>
<?php $__env->startSection('page-title', 'Inventory Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo e($summary['total_products']); ?></h3>
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
                <h3><?php echo e($currency); ?><?php echo e(number_format($summary['total_stock_value'], 2)); ?></h3>
                <p>Stock Value (Cost)</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="<?php echo e(route('products.index', ['stock' => 'low'])); ?>" class="text-decoration-none text-reset">
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo e($summary['low_stock_count']); ?></h3>
                    <p>Low Stock Items</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="<?php echo e(route('products.index', ['stock' => 'out'])); ?>" class="text-decoration-none text-reset">
            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo e($summary['out_of_stock_count']); ?></h3>
                    <p>Out of Stock</p>
                </div>
            </div>
        </a>
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
                    <?php $__currentLoopData = $categoryStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold"><?php echo e($category->name); ?></div>
                                <small class="text-muted"><?php echo e($category->products_count); ?> products</small>
                            </div>
                            <span class="badge bg-primary"><?php echo e($category->products_sum_quantity ?? 0); ?> units</span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($product->name); ?></td>
                                    <td><?php echo e($product->category->name); ?></td>
                                    <td><code><?php echo e($product->sku); ?></code></td>
                                    <td class="text-end"><?php echo e($product->quantity); ?> <?php echo e($product->unit); ?></td>
                                    <td class="text-end"><?php echo e($currency); ?><?php echo e(number_format($product->cost_price, 2)); ?></td>
                                    <td class="text-end fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($product->quantity * $product->cost_price, 2)); ?></td>
                                    <td>
                                        <?php if($product->quantity == 0): ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php elseif($product->isLowStock()): ?>
                                            <span class="badge bg-warning">Low Stock</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">In Stock</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/reports/inventory.blade.php ENDPATH**/ ?>