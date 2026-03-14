

<?php $__env->startSection('title', $product->name); ?>
<?php $__env->startSection('page-title', 'Product Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box-seam me-2"></i>Product Information</span>
                <div class="btn-group btn-group-sm">
                    <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <?php if($product->image): ?>
                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" 
                                 class="img-fluid rounded" style="max-height: 200px;">
                        <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 200px; height: 200px;">
                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-1"><?php echo e($product->name); ?></h4>
                        <p class="text-muted mb-3">
                            <span class="badge bg-primary"><?php echo e($product->category->name); ?></span>
                            <?php if($product->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">SKU</small>
                                <code><?php echo e($product->sku); ?></code>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Barcode</small>
                                <span><?php echo e($product->barcode ?: '-'); ?></span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-4">
                                <small class="text-muted d-block">Cost Price</small>
                                <span>₹<?php echo e(number_format($product->cost_price, 2)); ?></span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Selling Price</small>
                                <span class="fw-bold text-success">₹<?php echo e(number_format($product->selling_price, 2)); ?></span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Profit Margin</small>
                                <span>₹<?php echo e(number_format($product->getProfit(), 2)); ?></span>
                            </div>
                        </div>
                        
                        <?php if($product->description): ?>
                            <p class="text-muted"><?php echo e($product->description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Recent Sales
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $product->saleItems()->with('sale')->latest()->take(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('pos.receipt', $item->sale)); ?>"><?php echo e($item->sale->invoice_number); ?></a>
                                    </td>
                                    <td><?php echo e($item->quantity); ?></td>
                                    <td>₹<?php echo e(number_format($item->unit_price, 2)); ?></td>
                                    <td class="fw-semibold">₹<?php echo e(number_format($item->total, 2)); ?></td>
                                    <td><?php echo e($item->created_at->format('d M Y, h:i A')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No sales recorded</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-box me-2"></i>Stock Information
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h2 class="mb-0 <?php echo e($product->isLowStock() ? 'text-warning' : 'text-success'); ?>">
                        <?php echo e($product->quantity); ?>

                    </h2>
                    <small class="text-muted"><?php echo e(ucfirst($product->unit)); ?>s in stock</small>
                </div>
                
                <?php if($product->isLowStock()): ?>
                    <div class="alert alert-warning py-2 mb-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Low stock alert (below <?php echo e($product->alert_quantity); ?>)
                    </div>
                <?php endif; ?>
                
                <hr>
                
                <h6 class="mb-3">Adjust Stock</h6>
                <form action="<?php echo e(route('products.adjust-stock', $product)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="number" name="adjustment" class="form-control" min="1" value="1" required>
                        <select name="type" class="form-select" style="max-width: 120px;">
                            <option value="add">Add</option>
                            <option value="subtract">Subtract</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-arrow-repeat me-2"></i>Update Stock
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Details
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Created</span>
                    <span><?php echo e($product->created_at->format('d M Y')); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Last Updated</span>
                    <span><?php echo e($product->updated_at->format('d M Y')); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Total Sold</span>
                    <span><?php echo e($product->saleItems()->sum('quantity')); ?> <?php echo e($product->unit); ?>s</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/products/show.blade.php ENDPATH**/ ?>