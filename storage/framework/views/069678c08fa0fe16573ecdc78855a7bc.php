

<?php $__env->startSection('title', 'Settings'); ?>
<?php $__env->startSection('page-title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="<?php echo e(route('settings.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-globe me-2"></i>Timezone &amp; Locale
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Timezone <span class="text-danger">*</span></label>
                        <select name="timezone" class="form-select <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php
                                $currentTz = old('timezone', $settings['timezone'] ?? 'Asia/Dhaka');
                                $grouped = collect(timezone_identifiers_list())->groupBy(function ($tz) {
                                    if (str_starts_with($tz, 'Asia/')) return 'Asia';
                                    if (str_starts_with($tz, 'America/')) return 'America';
                                    if (str_starts_with($tz, 'Europe/')) return 'Europe';
                                    if (str_starts_with($tz, 'Africa/')) return 'Africa';
                                    if (str_starts_with($tz, 'Australia/')) return 'Australia';
                                    if (in_array($tz, ['UTC', 'GMT'])) return 'UTC';
                                    return 'Other';
                                });
                            ?>
                            <optgroup label="Asia (GMT+6 etc.)">
                                <?php $__currentLoopData = $grouped->get('Asia', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tz); ?>" <?php echo e($currentTz === $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="UTC">
                                <option value="UTC" <?php echo e($currentTz === 'UTC' ? 'selected' : ''); ?>>UTC</option>
                            </optgroup>
                            <optgroup label="Europe">
                                <?php $__currentLoopData = $grouped->get('Europe', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tz); ?>" <?php echo e($currentTz === $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="America">
                                <?php $__currentLoopData = $grouped->get('America', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tz); ?>" <?php echo e($currentTz === $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="Other">
                                <?php $__currentLoopData = $grouped->get('Other', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tz); ?>" <?php echo e($currentTz === $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        </select>
                        <small class="text-muted">Used for all reports and dates. Default: Asia/Dhaka (GMT+6).</small>
                        <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-currency-exchange me-2"></i>Currency Settings
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" name="currency_symbol" class="form-control <?php $__errorArgs = ['currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('currency_symbol', $settings['currency_symbol'])); ?>" required>
                            <small class="text-muted">e.g., ৳, $, €, ₹, £</small>
                            <?php $__errorArgs = ['currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" name="currency_code" class="form-control <?php $__errorArgs = ['currency_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('currency_code', $settings['currency_code'])); ?>" required>
                            <small class="text-muted">e.g., BDT, USD, EUR, INR, GBP</small>
                            <?php $__errorArgs = ['currency_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Default Tax Percentage <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="tax_percentage" step="0.01" min="0" max="100"
                                       class="form-control <?php $__errorArgs = ['tax_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       value="<?php echo e(old('tax_percentage', $settings['tax_percentage'])); ?>" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <?php $__errorArgs = ['tax_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-credit-card me-2"></i>Payment Methods
                </div>
                <div class="card-body">
                    <p class="text-muted small">These options will appear at checkout. Code is stored in the database (e.g. cash, card).</p>
                    <div id="paymentMethodsList">
                        <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $code = is_array($method) ? ($method['code'] ?? '') : '';
                                $name = is_array($method) ? ($method['name'] ?? '') : '';
                            ?>
                            <div class="row g-2 mb-2 payment-method-row">
                                <div class="col-5">
                                    <input type="text" name="payment_methods[code][]" class="form-control form-control-sm" 
                                           placeholder="Code (e.g. cash)" value="<?php echo e($code); ?>">
                                </div>
                                <div class="col-5">
                                    <input type="text" name="payment_methods[name][]" class="form-control form-control-sm" 
                                           placeholder="Display name" value="<?php echo e($name); ?>">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-payment-method" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addPaymentMethod">
                        <i class="bi bi-plus-lg me-1"></i>Add Payment Method
                    </button>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-shop me-2"></i>Shop Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Shop Name <span class="text-danger">*</span></label>
                        <input type="text" name="shop_name" class="form-control <?php $__errorArgs = ['shop_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('shop_name', $settings['shop_name'])); ?>" required>
                        <?php $__errorArgs = ['shop_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shop Address</label>
                        <textarea name="shop_address" class="form-control <?php $__errorArgs = ['shop_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  rows="2"><?php echo e(old('shop_address', $settings['shop_address'])); ?></textarea>
                        <?php $__errorArgs = ['shop_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shop Phone</label>
                        <input type="text" name="shop_phone" class="form-control <?php $__errorArgs = ['shop_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('shop_phone', $settings['shop_phone'])); ?>">
                        <?php $__errorArgs = ['shop_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<template id="paymentMethodRowTemplate">
    <div class="row g-2 mb-2 payment-method-row">
        <div class="col-5">
            <input type="text" name="payment_methods[code][]" class="form-control form-control-sm" placeholder="Code (e.g. cash)">
        </div>
        <div class="col-5">
            <input type="text" name="payment_methods[name][]" class="form-control form-control-sm" placeholder="Display name">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-danger btn-sm remove-payment-method" title="Remove">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</template>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('addPaymentMethod').addEventListener('click', function() {
    const tpl = document.getElementById('paymentMethodRowTemplate');
    const row = tpl.content.cloneNode(true);
    document.getElementById('paymentMethodsList').appendChild(row);
});

document.getElementById('paymentMethodsList').addEventListener('click', function(e) {
    if (e.target.closest('.remove-payment-method')) {
        e.target.closest('.payment-method-row').remove();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/settings/index.blade.php ENDPATH**/ ?>