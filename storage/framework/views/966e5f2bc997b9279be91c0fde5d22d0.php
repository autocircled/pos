

<?php $__env->startSection('title', 'Activity Log Entry'); ?>
<?php $__env->startSection('page-title', 'Activity Log Entry'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-journal-text me-2"></i>Log Details
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="140">Time</th>
                        <td><?php echo e($activityLog->created_at->format('M j, Y H:i:s')); ?></td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td><?php echo e($activityLog->user?->name ?? 'System'); ?> <?php if($activityLog->user): ?><span class="text-muted">(<?php echo e($activityLog->user->email); ?>)</span><?php endif; ?></td>
                    </tr>
                    <tr>
                        <th>Action</th>
                        <td>
                            <?php if($activityLog->action === 'created'): ?>
                                <span class="badge bg-success">Created</span>
                            <?php elseif($activityLog->action === 'updated'): ?>
                                <span class="badge bg-primary">Updated</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Deleted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td><?php echo e(class_basename($activityLog->subject_type)); ?> <?php if($activityLog->subject_id): ?> #<?php echo e($activityLog->subject_id); ?> <?php endif; ?></td>
                    </tr>
                    <?php if($activityLog->description): ?>
                        <tr>
                            <th>Description</th>
                            <td><?php echo e($activityLog->description); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <?php if($activityLog->action === 'updated' && $activityLog->getChangesSummary()): ?>
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-arrow-left-right me-2"></i>Changes
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Fields that were modified in this update:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Old value</th>
                                    <th>New value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $activityLog->getChangesSummary(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="fw-semibold"><?php echo e(str_replace('_', ' ', ucfirst($field))); ?></td>
                                        <td><code><?php echo e(is_bool($change['old']) ? ($change['old'] ? 'Yes' : 'No') : (string) $change['old']); ?></code></td>
                                        <td><code><?php echo e(is_bool($change['new']) ? ($change['new'] ? 'Yes' : 'No') : (string) $change['new']); ?></code></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($activityLog->action === 'created' && $activityLog->new_values): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-plus-circle me-2"></i>Created data
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $activityLog->new_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!in_array($field, ['created_at', 'updated_at'])): ?>
                                        <tr>
                                            <td class="fw-semibold"><?php echo e(str_replace('_', ' ', ucfirst($field))); ?></td>
                                            <td><code><?php echo e(is_bool($value) ? ($value ? 'Yes' : 'No') : (string) $value); ?></code></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($activityLog->action === 'deleted' && $activityLog->old_values): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-trash me-2"></i>Deleted data (snapshot)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $activityLog->old_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!in_array($field, ['created_at', 'updated_at'])): ?>
                                        <tr>
                                            <td class="fw-semibold"><?php echo e(str_replace('_', ' ', ucfirst($field))); ?></td>
                                            <td><code><?php echo e(is_bool($value) ? ($value ? 'Yes' : 'No') : (string) $value); ?></code></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-3">
            <a href="<?php echo e(route('activity-log.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Activity Log
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/activity-log/show.blade.php ENDPATH**/ ?>