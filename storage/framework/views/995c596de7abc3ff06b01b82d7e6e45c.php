

<?php $__env->startSection('title', 'Point of Sale'); ?>
<?php $__env->startSection('page-title', 'Point of Sale'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .pos-container {
        display: flex;
        gap: 1.5rem;
        height: calc(100vh - 140px);
    }
    .products-section {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .cart-section {
        width: 400px;
        display: flex;
        flex-direction: column;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.75rem;
        overflow-y: auto;
        padding: 0.5rem;
    }
    .product-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .product-card:hover {
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }
    .product-card.out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .product-card img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }
    .product-card .name {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .product-card .price {
        color: #4f46e5;
        font-weight: 600;
    }
    .product-card .stock {
        font-size: 0.7rem;
        color: #64748b;
    }
    .cart-items {
        flex: 1;
        overflow-y: auto;
    }
    .cart-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        gap: 0.75rem;
    }
    .cart-item .info {
        flex: 1;
    }
    .cart-item .name {
        font-weight: 500;
        font-size: 0.9rem;
    }
    .cart-item .price {
        color: #64748b;
        font-size: 0.8rem;
    }
    .qty-control {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .qty-control button {
        width: 28px;
        height: 28px;
        border: none;
        background: #f1f5f9;
        border-radius: 0.25rem;
        font-weight: 600;
    }
    .qty-control input {
        width: 40px;
        text-align: center;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        padding: 0.25rem;
    }
    .cart-summary {
        background: #f8fafc;
        padding: 1rem;
        border-top: 2px solid #e2e8f0;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .summary-row.total {
        font-size: 1.25rem;
        font-weight: 700;
        border-top: 1px solid #e2e8f0;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }
    .category-pills {
        display: flex;
        gap: 0.5rem;
        padding: 0.5rem 0;
        overflow-x: auto;
        flex-wrap: wrap;
    }
    .category-pill {
        padding: 0.5rem 1rem;
        background: #f1f5f9;
        border: none;
        border-radius: 2rem;
        font-size: 0.85rem;
        cursor: pointer;
        white-space: nowrap;
    }
    .category-pill.active {
        background: #4f46e5;
        color: #fff;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pos-container">
    <div class="products-section">
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, SKU or barcode...">
                        </div>
                    </div>
                </div>
                <div class="category-pills">
                    <button class="category-pill active" data-category="">All</button>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button class="category-pill" data-category="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        
        <div class="card flex-grow-1">
            <div class="products-grid" id="productsGrid">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-card <?php echo e($product->quantity <= 0 ? 'out-of-stock' : ''); ?>" 
                         data-product="<?php echo e(json_encode($product)); ?>"
                         data-category="<?php echo e($product->category_id); ?>">
                        <?php if($product->image): ?>
                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>">
                        <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-box text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="name" title="<?php echo e($product->name); ?>"><?php echo e($product->name); ?></div>
                        <div class="price">₹<?php echo e(number_format($product->selling_price, 2)); ?></div>
                        <div class="stock"><?php echo e($product->quantity); ?> in stock</div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    
    <div class="cart-section">
        <div class="card flex-grow-1 d-flex flex-column">
            <div class="card-header">
                <i class="bi bi-cart3 me-2"></i>Current Sale
                <button class="btn btn-sm btn-outline-danger float-end" id="clearCart">Clear</button>
            </div>
            
            <div class="cart-items" id="cartItems">
                <div class="text-center text-muted py-5" id="emptyCart">
                    <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                    <p>Cart is empty</p>
                    <small>Click on products to add them</small>
                </div>
            </div>
            
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal">₹0.00</span>
                </div>
                <div class="summary-row">
                    <span>Discount</span>
                    <div class="input-group input-group-sm" style="width: 120px;">
                        <span class="input-group-text">₹</span>
                        <input type="number" id="discount" class="form-control" value="0" min="0" step="0.01">
                    </div>
                </div>
                <div class="summary-row">
                    <span>Tax</span>
                    <div class="input-group input-group-sm" style="width: 120px;">
                        <span class="input-group-text">₹</span>
                        <input type="number" id="tax" class="form-control" value="0" min="0" step="0.01">
                    </div>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="total">₹0.00</span>
                </div>
            </div>
            
            <div class="p-3">
                <button class="btn btn-primary btn-lg w-100" id="checkoutBtn" disabled>
                    <i class="bi bi-credit-card me-2"></i>Checkout
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-credit-card me-2"></i>Complete Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" id="customerName" class="form-control" placeholder="Optional">
                </div>
                <div class="mb-3">
                    <label class="form-label">Customer Phone</label>
                    <input type="text" id="customerPhone" class="form-control" placeholder="Optional">
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="paymentMethod" id="paymentCash" value="cash" checked>
                        <label class="btn btn-outline-primary" for="paymentCash"><i class="bi bi-cash me-1"></i>Cash</label>
                        
                        <input type="radio" class="btn-check" name="paymentMethod" id="paymentCard" value="card">
                        <label class="btn btn-outline-primary" for="paymentCard"><i class="bi bi-credit-card me-1"></i>Card</label>
                        
                        <input type="radio" class="btn-check" name="paymentMethod" id="paymentUPI" value="upi">
                        <label class="btn btn-outline-primary" for="paymentUPI"><i class="bi bi-phone me-1"></i>UPI</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Total Amount</label>
                        <input type="text" id="modalTotal" class="form-control fw-bold" readonly>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Paid Amount</label>
                        <input type="number" id="paidAmount" class="form-control" min="0" step="0.01">
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded text-center">
                    <small class="text-muted">Change</small>
                    <h3 id="changeAmount" class="mb-0 text-success">₹0.00</h3>
                </div>
                <div class="mb-3 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea id="saleNotes" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="completeSale">
                    <i class="bi bi-check-lg me-2"></i>Complete Sale
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let cart = [];
const products = <?php echo json_encode($products, 15, 512) ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Category filter
    document.querySelectorAll('.category-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            filterProducts();
        });
    });
    
    // Search
    document.getElementById('searchInput').addEventListener('input', filterProducts);
    
    // Product click
    document.querySelectorAll('.product-card:not(.out-of-stock)').forEach(card => {
        card.addEventListener('click', function() {
            const product = JSON.parse(this.dataset.product);
            addToCart(product);
        });
    });
    
    // Clear cart
    document.getElementById('clearCart').addEventListener('click', clearCart);
    
    // Discount/Tax change
    document.getElementById('discount').addEventListener('input', updateTotals);
    document.getElementById('tax').addEventListener('input', updateTotals);
    
    // Checkout button
    document.getElementById('checkoutBtn').addEventListener('click', openCheckoutModal);
    
    // Paid amount change
    document.getElementById('paidAmount').addEventListener('input', calculateChange);
    
    // Complete sale
    document.getElementById('completeSale').addEventListener('click', completeSale);
});

function filterProducts() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const categoryId = document.querySelector('.category-pill.active').dataset.category;
    
    document.querySelectorAll('.product-card').forEach(card => {
        const product = JSON.parse(card.dataset.product);
        const matchesSearch = product.name.toLowerCase().includes(search) || 
                             product.sku.toLowerCase().includes(search) ||
                             (product.barcode && product.barcode.toLowerCase().includes(search));
        const matchesCategory = !categoryId || card.dataset.category == categoryId;
        
        card.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
    });
}

function addToCart(product) {
    const existingItem = cart.find(item => item.product_id === product.id);
    
    if (existingItem) {
        if (existingItem.quantity < product.quantity) {
            existingItem.quantity++;
        } else {
            alert('Not enough stock available');
            return;
        }
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            price: parseFloat(product.selling_price),
            quantity: 1,
            max_qty: product.quantity,
            discount: 0
        });
    }
    
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');
    
    if (cart.length === 0) {
        emptyCart.style.display = 'block';
        container.innerHTML = '';
        container.appendChild(emptyCart);
        document.getElementById('checkoutBtn').disabled = true;
        updateTotals();
        return;
    }
    
    emptyCart.style.display = 'none';
    document.getElementById('checkoutBtn').disabled = false;
    
    container.innerHTML = cart.map((item, index) => `
        <div class="cart-item">
            <div class="info">
                <div class="name">${item.name}</div>
                <div class="price">₹${item.price.toFixed(2)} × ${item.quantity} = ₹${(item.price * item.quantity).toFixed(2)}</div>
            </div>
            <div class="qty-control">
                <button onclick="updateQuantity(${index}, -1)">-</button>
                <input type="number" value="${item.quantity}" min="1" max="${item.max_qty}" 
                       onchange="setQuantity(${index}, this.value)">
                <button onclick="updateQuantity(${index}, 1)">+</button>
            </div>
            <button class="btn btn-sm btn-link text-danger" onclick="removeFromCart(${index})">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `).join('');
    
    updateTotals();
}

function updateQuantity(index, delta) {
    const item = cart[index];
    const newQty = item.quantity + delta;
    
    if (newQty >= 1 && newQty <= item.max_qty) {
        item.quantity = newQty;
        renderCart();
    }
}

function setQuantity(index, value) {
    const item = cart[index];
    const qty = parseInt(value);
    
    if (qty >= 1 && qty <= item.max_qty) {
        item.quantity = qty;
        renderCart();
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function clearCart() {
    if (cart.length > 0 && confirm('Clear all items from cart?')) {
        cart = [];
        renderCart();
    }
}

function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const total = subtotal - discount + tax;
    
    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '₹' + total.toFixed(2);
}

function openCheckoutModal() {
    const total = parseFloat(document.getElementById('total').textContent.replace('₹', ''));
    document.getElementById('modalTotal').value = '₹' + total.toFixed(2);
    document.getElementById('paidAmount').value = total.toFixed(2);
    calculateChange();
    
    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
}

function calculateChange() {
    const total = parseFloat(document.getElementById('total').textContent.replace('₹', ''));
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    const change = paid - total;
    
    document.getElementById('changeAmount').textContent = '₹' + Math.max(0, change).toFixed(2);
    document.getElementById('changeAmount').className = change >= 0 ? 'mb-0 text-success' : 'mb-0 text-danger';
}

function completeSale() {
    const total = parseFloat(document.getElementById('total').textContent.replace('₹', ''));
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    
    if (paid < total) {
        alert('Paid amount cannot be less than total');
        return;
    }
    
    const data = {
        items: cart.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            discount: item.discount
        })),
        customer_name: document.getElementById('customerName').value,
        customer_phone: document.getElementById('customerPhone').value,
        discount: parseFloat(document.getElementById('discount').value) || 0,
        tax: parseFloat(document.getElementById('tax').value) || 0,
        paid_amount: paid,
        payment_method: document.querySelector('input[name="paymentMethod"]:checked').value,
        notes: document.getElementById('saleNotes').value
    };
    
    fetch('<?php echo e(route("pos.checkout")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            alert(result.message);
        }
    })
    .catch(error => {
        alert('An error occurred. Please try again.');
        console.error(error);
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\mukul\workspace\pos\resources\views/pos/index.blade.php ENDPATH**/ ?>