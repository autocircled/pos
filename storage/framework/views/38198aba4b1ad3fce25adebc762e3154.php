

<?php $__env->startSection('title', 'Activity Log'); ?>
<?php $__env->startSection('page-title', 'Activity Log'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <p class="text-muted mb-0">View actions performed by users (e.g. product edits and changes).</p>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('activity-log.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="user" class="form-select">
                    <option value="">All Users</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php echo e(request('user') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?> (<?php echo e($u->email); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="created" <?php echo e(request('action') === 'created' ? 'selected' : ''); ?>>Created</option>
                    <option value="updated" <?php echo e(request('action') === 'updated' ? 'selected' : ''); ?>>Updated</option>
                    <option value="deleted" <?php echo e(request('action') === 'deleted' ? 'selected' : ''); ?>>Deleted</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="subject" class="form-select">
                    <option value="">All Subjects</option>
                    <option value="App\Models\Product" <?php echo e(request('subject') === 'App\Models\Product' ? 'selected' : ''); ?>>Product</option>
                </select>
            </div>
            <div class="col-md-2">
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
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th width="80"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($log->created_at->format('M j, Y H:i')); ?></td>
                            <td><?php echo e($log->user?->name ?? 'System'); ?></td>
                            <td>
                                <?php if($log->action === 'created'): ?>
                                    <span class="badge bg-success">Created</span>
                                <?php elseif($log->action === 'updated'): ?>
                                    <span class="badge bg-primary">Updated</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Deleted</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($log->subject_type === 'App\Models\Product'): ?>
                                    Product
                                    <?php if($log->subject_id): ?>
                                        #<?php echo e($log->subject_id); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo e(class_basename($log->subject_type)); ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo e($log->description ?? '-'); ?></td>
                            <td>
                                <a href="<?php echo e(route('activity-log.show', $log)); ?>" class="btn btn-outline-primary btn-sm" title="View details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No activity logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($logs->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($logs->withQueryString()->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/activity-log/index.blade.php ENDPATH**/ ?>