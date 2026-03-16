

<?php $__env->startSection('title', 'Receipt - ' . $sale->invoice_number); ?>
<?php $__env->startSection('page-title', 'Sale Receipt'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .receipt {
        max-width: 400px;
        margin: 0 auto;
        background: #fff;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .receipt-header {
        text-align: center;
        border-bottom: 2px dashed #e2e8f0;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    .receipt-header h3 {
        margin: 0;
        font-weight: 700;
    }
    .receipt-items {
        border-bottom: 2px dashed #e2e8f0;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    .receipt-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .receipt-item .qty {
        color: #64748b;
    }
    .receipt-totals {
        margin-bottom: 1rem;
    }
    .receipt-totals .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }
    .receipt-totals .total {
        font-size: 1.25rem;
        font-weight: 700;
        border-top: 1px solid #e2e8f0;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }
    .receipt-footer {
        text-align: center;
        color: #64748b;
        font-size: 0.85rem;
    }
    @media print {
        .no-print { display: none; }
        .receipt { box-shadow: none; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3 no-print">
    <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-2"></i>New Sale
    </a>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-2"></i>Print Receipt
    </button>
</div>

<div class="receipt">
    <div class="receipt-header">
        <h3><i class="bi bi-pencil-square me-2"></i>Stationery POS</h3>
        <p class="mb-0 text-muted">Invoice: <?php echo e($sale->invoice_number); ?></p>
        <small><?php echo e($sale->created_at->format('d M Y, h:i A')); ?></small>
    </div>
    
    <?php if($sale->customer_name): ?>
        <div class="mb-3">
            <strong>Customer:</strong> <?php echo e($sale->customer_name); ?><br>
            <?php if($sale->customer_phone): ?>
                <strong>Phone:</strong> <?php echo e($sale->customer_phone); ?>

            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="receipt-items">
        <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="receipt-item">
                <div>
                    <div><?php echo e($item->product_name); ?></div>
                    <div class="qty"><?php echo e($item->quantity); ?> × <?php echo e($currency); ?><?php echo e(number_format($item->unit_price, 2)); ?></div>
                </div>
                <div class="fw-semibold"><?php echo e($currency); ?><?php echo e(number_format($item->total, 2)); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <div class="receipt-totals">
        <div class="row">
            <span>Subtotal</span>
            <span><?php echo e($currency); ?><?php echo e(number_format($sale->subtotal, 2)); ?></span>
        </div>
        <?php if($sale->discount > 0): ?>
            <div class="row text-danger">
                <span>Discount</span>
                <span>-<?php echo e($currency); ?><?php echo e(number_format($sale->discount, 2)); ?></span>
            </div>
        <?php endif; ?>
        <?php if($sale->tax > 0): ?>
            <div class="row">
                <span>Tax</span>
                <span><?php echo e($currency); ?><?php echo e(number_format($sale->tax, 2)); ?></span>
            </div>
        <?php endif; ?>
        <div class="row total">
            <span>Total</span>
            <span><?php echo e($currency); ?><?php echo e(number_format($sale->total, 2)); ?></span>
        </div>
        <div class="row">
            <span>Paid (<?php echo e(ucfirst($sale->payment_method)); ?>)</span>
            <span><?php echo e($currency); ?><?php echo e(number_format($sale->paid_amount, 2)); ?></span>
        </div>
        <?php if($sale->change_amount > 0): ?>
            <div class="row">
                <span>Change</span>
                <span><?php echo e($currency); ?><?php echo e(number_format($sale->change_amount, 2)); ?></span>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="receipt-footer">
        <p class="mb-1">Served by: <?php echo e($sale->user->name); ?></p>
        <p class="mb-0">Thank you for shopping with us!</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/pos/receipt.blade.php ENDPATH**/ ?>