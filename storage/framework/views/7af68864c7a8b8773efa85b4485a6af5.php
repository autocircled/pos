

<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('page-title', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Manage your product categories</p>
    </div>
    <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Category
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($category->id); ?></td>
                            <td class="fw-semibold"><?php echo e($category->name); ?></td>
                            <td><?php echo e(Str::limit($category->description, 50) ?: '-'); ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo e($category->products_count); ?> products</span>
                            </td>
                            <td>
                                <?php if($category->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($category->created_at->format('d M Y')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-folder-x fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No categories found</p>
                                <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary mt-3">Create First Category</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($categories->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($categories->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/categories/index.blade.php ENDPATH**/ ?>