

<?php $__env->startSection('title', 'Sales History'); ?>
<?php $__env->startSection('page-title', 'Sales History'); ?>

<?php $__env->startSection('content'); ?>
<div class="card mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('pos.history')); ?>" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="<?php echo e(route('pos.history')); ?>" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('pos.receipt', $sale)); ?>" class="fw-semibold">
                                    <?php echo e($sale->invoice_number); ?>

                                </a>
                            </td>
                            <td><?php echo e($sale->customer_name ?: 'Walk-in'); ?></td>
                            <td><?php echo e($sale->items->count()); ?> items</td>
                            <td class="fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($sale->total, 2)); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($sale->payment_method === 'cash' ? 'success' : ($sale->payment_method === 'card' ? 'primary' : 'info')); ?>">
                                    <?php echo e(ucfirst($sale->payment_method)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($sale->status === 'completed'): ?>
                                    <span class="badge bg-success">Completed</span>
                                <?php elseif($sale->status === 'cancelled'): ?>
                                    <span class="badge bg-danger">Cancelled</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($sale->created_at->format('d M Y, h:i A')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('pos.receipt', $sale)); ?>" class="btn btn-outline-primary" title="View Receipt">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    <?php if($sale->status === 'completed'): ?>
                                        <form action="<?php echo e(route('pos.cancel', $sale)); ?>" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to cancel this sale? Stock will be restored.')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-outline-danger" title="Cancel Sale">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-receipt-cutoff fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No sales found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($sales->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($sales->withQueryString()->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/pos/history.blade.php ENDPATH**/ ?>