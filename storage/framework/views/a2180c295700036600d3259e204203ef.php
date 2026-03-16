

<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('page-title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Manage your stationery inventory</p>
    </div>
    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Product
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('products.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo e(request('search')); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <input type="text" name="company" class="form-control" placeholder="Company..." value="<?php echo e(request('company')); ?>">
                </div>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock" class="form-select">
                    <option value="">All Stock Levels</option>
                    <option value="low" <?php echo e(request('stock') == 'low' ? 'selected' : ''); ?>>Low Stock</option>
                    <option value="out" <?php echo e(request('stock') == 'out' ? 'selected' : ''); ?>>Out of Stock</option>
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
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($product->image): ?>
                                        <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" 
                                             class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-box text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold"><?php echo e($product->name); ?></div>
                                        <?php if($product->company): ?>
                                            <small class="text-muted d-block"><?php echo e($product->company); ?></small>
                                        <?php endif; ?>
                                        <?php if($product->barcode): ?>
                                            <small class="text-muted"><?php echo e($product->barcode); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($product->category->name); ?></td>
                            <td><code><?php echo e($product->sku); ?></code></td>
                            <td><?php echo e($currency); ?><?php echo e(number_format($product->cost_price, 2)); ?></td>
                            <td class="fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($product->selling_price, 2)); ?></td>
                            <td>
                                <?php if($product->quantity == 0): ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                <?php elseif($product->isLowStock()): ?>
                                    <span class="badge bg-warning"><?php echo e($product->quantity); ?> <?php echo e($product->unit); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo e($product->quantity); ?> <?php echo e($product->unit); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($product->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?php echo e(route('products.duplicate', $product)); ?>" class="btn btn-outline-secondary" title="Duplicate">
                                        <i class="bi bi-copy"></i>
                                    </a>
                                    <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-box-seam fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No products found</p>
                                <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary mt-3">Add First Product</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($products->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($products->withQueryString()->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/products/index.blade.php ENDPATH**/ ?>