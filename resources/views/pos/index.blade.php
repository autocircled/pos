@extends('layouts.app')

@section('title', 'Point of Sale')
@section('page-title', 'Point of Sale')

@push('styles')
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
    .qty-control .qty-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: #f1f5f9;
        border-radius: 0.25rem;
        font-weight: 600;
        cursor: pointer;
    }
    .qty-control .qty-btn:hover {
        background: #e2e8f0;
    }
    .qty-control button {
        width: 28px;
        height: 28px;
        border: none;
        background: #f1f5f9;
        border-radius: 0.25rem;
        font-weight: 600;
        cursor: pointer;
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
@endpush

@section('content')
<div class="pos-container">
    <div class="products-section">
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, SKU, barcode or company...">
                        </div>
                    </div>
                </div>
                <div class="category-pills">
                    <button class="category-pill active" data-category="">All</button>
                    @foreach($categories as $category)
                        <button class="category-pill" data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="card flex-grow-1">
            <div class="products-grid" id="productsGrid">
                @foreach($products as $product)
                    <div class="product-card {{ $product->quantity <= 0 ? 'out-of-stock' : '' }}" 
                         data-product="{{ json_encode($product) }}"
                         data-category="{{ $product->category_id }}">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-box text-muted"></i>
                            </div>
                        @endif
                        <div class="name" title="{{ $product->name }}">
                            {{ $product->name }}
                            @if($product->requires_custom_price)
                                <span class="badge bg-info text-white ms-1" style="font-size: 0.6em;" title="Requires Custom Price">
                                    <i class="bi bi-price-tag"></i>
                                </span>
                            @endif
                        </div>
                        @if($product->company)
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $product->company }}</div>
                        @endif
                        <div class="price">{{ $currency }}{{ number_format($product->selling_price, 2) }}</div>
                        <div class="stock">{{ $product->quantity }} in stock</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="cart-section">
        <div class="card flex-grow-1 d-flex flex-column">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cart3 me-2"></i>
                        <select id="cartSelector" class="form-select form-select-sm me-2" style="width: auto;">
                            <!-- Cart options will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-success" id="newCartBtn" title="New Cart">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <button class="btn btn-outline-danger" id="deleteCartBtn" title="Delete Cart">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-outline-warning" id="clearCart" title="Clear Current Cart">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
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
                    <span id="subtotal">{{ $currency }}0.00</span>
                </div>
                <div class="summary-row">
                    <span>Discount</span>
                    <div class="input-group input-group-sm" style="width: 120px;">
                        <span class="input-group-text">{{ $currency }}</span>
                        <input type="number" id="discount" class="form-control" value="0" min="0" step="1">
                    </div>
                </div>
                <div class="summary-row">
                    <span>Tax</span>
                    <div class="input-group input-group-sm" style="width: 120px;">
                        <span class="input-group-text">{{ $currency }}</span>
                        <input type="number" id="tax" class="form-control" value="0" min="0" step="1">
                    </div>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="total">{{ $currency }}0.00</span>
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
                    <label class="form-label">Sale Date</label>
                    <input type="date" id="saleDate" class="form-control"
                           value="{{ now()->toDateString() }}"
                           max="{{ now()->toDateString() }}">
                    <small class="text-muted">Backdated sales are allowed. Future dates are not allowed.</small>
                </div>
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
                    <div class="btn-group w-100 flex-wrap" role="group">
                        @foreach($paymentMethods as $idx => $method)
                            <input type="radio" class="btn-check" name="paymentMethod" id="payment{{ $method['code'] }}" 
                                   value="{{ $method['code'] }}" {{ $idx === 0 ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="payment{{ $method['code'] }}">
                                {{ $method['name'] }}
                            </label>
                        @endforeach
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
                <div class="row mt-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <small class="text-muted">Change</small>
                            <h4 id="changeAmount" class="mb-0 text-success">{{ $currency }}0.00</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <small class="text-muted">Due Amount</small>
                            <h4 id="dueAmount" class="mb-0 text-danger">{{ $currency }}0.00</h4>
                        </div>
                    </div>
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

<!-- Custom Price Modal -->
<div class="modal fade" id="customPriceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-price-tag me-2"></i>Set Custom Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Product</label>
                    <input type="text" id="customPriceProductName" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Custom Price <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">{{ $currency }}</span>
                        <input type="number" id="customPriceInput" class="form-control" step="0.01" min="0" 
                               placeholder="0.00" required autofocus>
                    </div>
                    <small class="text-muted">Enter the custom price for this service/product</small>
                </div>
                <div id="customPriceError" class="alert alert-danger d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCustomPrice">
                    <i class="bi bi-check-lg me-2"></i>Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Multi-cart system with local storage persistence
let carts = {};
let currentCartId = 'default';
let emptyCartEl = null;
const cs = (typeof window.currencySymbol !== 'undefined') ? window.currencySymbol : '৳';

// Cart management functions
function loadCartsFromStorage() {
    const saved = localStorage.getItem('posCarts');
    if (saved) {
        carts = JSON.parse(saved);
    } else {
        carts = {
            'default': {
                id: 'default',
                name: 'Default Cart',
                customerName: '',
                items: [],
                createdAt: new Date().toISOString()
            }
        };
    }
    
    // Load current cart ID
    const savedCurrentCart = localStorage.getItem('posCurrentCartId');
    if (savedCurrentCart && carts[savedCurrentCart]) {
        currentCartId = savedCurrentCart;
    }
}

function saveCartsToStorage() {
    localStorage.setItem('posCarts', JSON.stringify(carts));
    localStorage.setItem('posCurrentCartId', currentCartId);
}

function getCurrentCart() {
    if (!carts[currentCartId]) {
        currentCartId = 'default';
    }
    return carts[currentCartId];
}

function getCurrentCartItems() {
    return getCurrentCart().items || [];
}

function setCurrentCartItems(items) {
    getCurrentCart().items = items;
    saveCartsToStorage();
}

function createNewCart(customerName = '') {
    const cartId = 'cart_' + Date.now();
    const cartName = customerName || 'Cart ' + (Object.keys(carts).length + 1);
    
    carts[cartId] = {
        id: cartId,
        name: cartName,
        customerName: customerName,
        items: [],
        createdAt: new Date().toISOString()
    };
    
    currentCartId = cartId;
    saveCartsToStorage();
    updateCartSelector();
    renderCart();
    
    return cartId;
}

function switchCart(cartId) {
    if (carts[cartId]) {
        currentCartId = cartId;
        saveCartsToStorage();
        updateCartSelector();
        renderCart();
        updateTotals();
    }
}

function deleteCart(cartId) {
    if (cartId === 'default') {
        alert('Cannot delete default cart');
        return;
    }
    
    if (confirm('Delete this cart and all its items?')) {
        delete carts[cartId];
        
        if (currentCartId === cartId) {
            currentCartId = 'default';
        }
        
        saveCartsToStorage();
        updateCartSelector();
        renderCart();
        updateTotals();
    }
}

function updateCartSelector() {
    const selector = document.getElementById('cartSelector');
    if (!selector) return;
    
    selector.innerHTML = '';
    
    Object.keys(carts).forEach(cartId => {
        const cart = carts[cartId];
        const option = document.createElement('option');
        option.value = cartId;
        option.textContent = cart.name + (cart.items.length > 0 ? ' (' + cart.items.length + ')' : '');
        option.selected = cartId === currentCartId;
        selector.appendChild(option);
    });
}

function updateCurrentCartName(customerName) {
    const cart = getCurrentCart();
    cart.customerName = customerName;
    
    if (customerName && cart.id === 'default') {
        cart.name = customerName;
    } else if (!customerName && cart.id !== 'default') {
        cart.name = 'Cart ' + cart.id.replace('cart_', '');
    }
    
    saveCartsToStorage();
    updateCartSelector();
}

// Note: cart variable is now managed through getCurrentCartItems() and setCurrentCartItems() functions

document.addEventListener('DOMContentLoaded', function() {
    loadCartsFromStorage();
    emptyCartEl = document.getElementById('emptyCart');
    
    // Initialize cart selector
    updateCartSelector();
    
    // Cart selector change event
    const cartSelector = document.getElementById('cartSelector');
    if (cartSelector) {
        cartSelector.addEventListener('change', function() {
            switchCart(this.value);
        });
    }
    
    // New cart button
    const newCartBtn = document.getElementById('newCartBtn');
    if (newCartBtn) {
        newCartBtn.addEventListener('click', function() {
            const customerName = prompt('Enter customer name (optional):');
            createNewCart(customerName);
        });
    }
    
    // Delete cart button
    const deleteCartBtn = document.getElementById('deleteCartBtn');
    if (deleteCartBtn) {
        deleteCartBtn.addEventListener('click', function() {
            deleteCart(currentCartId);
        });
    }
    
    // Customer name change
    const customerNameInput = document.getElementById('customerName');
    if (customerNameInput) {
        customerNameInput.addEventListener('input', function() {
            updateCurrentCartName(this.value);
        });
        
        // Set initial customer name
        const currentCart = getCurrentCart();
        if (currentCart.customerName) {
            customerNameInput.value = currentCart.customerName;
        }
    }
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
    document.getElementById('clearCart').addEventListener('click', function(e) {
        e.preventDefault();
        clearCart();
    });
    
    // Discount/Tax change
    document.getElementById('discount').addEventListener('input', updateTotals);
    document.getElementById('tax').addEventListener('input', updateTotals);
    
    // Checkout button
    document.getElementById('checkoutBtn').addEventListener('click', openCheckoutModal);
    
    // Paid amount change
    document.getElementById('paidAmount').addEventListener('input', calculateChange);
    
    // Complete sale
    document.getElementById('completeSale').addEventListener('click', completeSale);
    
    // Event delegation for cart items (quantity buttons, remove, qty input)
    document.getElementById('cartItems').addEventListener('click', function(e) {
        const btn = e.target.closest('button[data-cart-index]');
        if (!btn) return;
        e.preventDefault();
        const index = parseInt(btn.getAttribute('data-cart-index'), 10);
        const action = btn.getAttribute('data-cart-action');
        if (action === 'decrease') {
            updateQuantity(index, -1);
        } else if (action === 'increase') {
            updateQuantity(index, 1);
        } else if (action === 'remove') {
            removeFromCart(index);
        }
    });
    
    document.getElementById('cartItems').addEventListener('input', function(e) {
        const input = e.target.closest('input[data-cart-qty]');
        if (!input) return;
        const index = parseInt(input.getAttribute('data-cart-index'), 10);
        const val = parseInt(input.value, 10);
        if (!isNaN(val)) setQuantity(index, val);
    });
    
    document.getElementById('cartItems').addEventListener('change', function(e) {
        const input = e.target.closest('input[data-cart-qty]');
        if (!input) return;
        const index = parseInt(input.getAttribute('data-cart-index'), 10);
        const val = parseInt(input.value, 10);
        if (!isNaN(val)) setQuantity(index, val);
    });
    
    // Custom price modal event listener
    document.getElementById('confirmCustomPrice').addEventListener('click', function() {
        const priceInput = document.getElementById('customPriceInput');
        const price = parseFloat(priceInput.value);
        const errorDiv = document.getElementById('customPriceError');
        
        // Validate price
        if (!price || price <= 0 || isNaN(price)) {
            errorDiv.textContent = 'Please enter a valid price greater than 0';
            errorDiv.classList.remove('d-none');
            priceInput.focus();
            return;
        }
        
        // Add product to cart with custom price
        if (window.pendingCustomPriceProduct) {
            addProductToCart(window.pendingCustomPriceProduct, price);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('customPriceModal'));
            modal.hide();
            
            // Clear pending product
            window.pendingCustomPriceProduct = null;
        }
    });
    
    // Allow Enter key in custom price input
    document.getElementById('customPriceInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('confirmCustomPrice').click();
        }
    });
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
    // Check if product requires custom price
    if (product.requires_custom_price) {
        showCustomPriceModal(product);
        return;
    }
    
    addProductToCart(product, parseFloat(product.selling_price));
}

function showCustomPriceModal(product) {
    // Set product name in modal
    document.getElementById('customPriceProductName').value = product.name;
    document.getElementById('customPriceInput').value = '';
    document.getElementById('customPriceError').classList.add('d-none');
    
    // Store product for later use
    window.pendingCustomPriceProduct = product;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('customPriceModal'));
    modal.show();
    
    // Focus on price input
    setTimeout(() => {
        document.getElementById('customPriceInput').focus();
    }, 500);
}

function addProductToCart(product, price) {
    const currentItems = getCurrentCartItems();
    const existingItem = currentItems.find(item => item.product_id === product.id);
    
    if (existingItem) {
        if (existingItem.quantity < product.quantity) {
            existingItem.quantity++;
            // Update price if it's a custom price item
            if (existingItem.custom_price) {
                existingItem.price = price;
                existingItem.custom_price = price;
            }
        } else {
            alert('Not enough stock available');
            return;
        }
    } else {
        currentItems.push({
            product_id: product.id,
            name: product.name,
            price: price,
            custom_price: product.requires_custom_price ? price : null,
            quantity: 1,
            max_qty: product.quantity,
            discount: 0
        });
    }
    
    setCurrentCartItems(currentItems);
    renderCart();
    updateTotals();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const currentItems = getCurrentCartItems();
    
    if (currentItems.length === 0) {
        container.innerHTML = '';
        if (emptyCartEl) {
            emptyCartEl.style.display = 'block';
            emptyCartEl.classList.remove('d-none');
            container.appendChild(emptyCartEl);
        }
        document.getElementById('checkoutBtn').disabled = true;
        updateTotals();
        return;
    }
    
    if (emptyCartEl && emptyCartEl.parentNode) {
        emptyCartEl.remove();
        emptyCartEl.style.display = 'none';
    }
    document.getElementById('checkoutBtn').disabled = false;
    
    container.innerHTML = currentItems.map((item, index) => `
        <div class="cart-item" data-cart-item="${index}">
            <div class="info">
                <div class="name">
                    <a href="/products/${item.product_id}" target="_blank">${escapeHtml(item.name)}</a>
                    ${item.custom_price ? '<span class="badge bg-info text-white ms-2" title="Custom Price"><i class="bi bi-price-tag"></i> Custom</span>' : ''}
                </div>
                <div class="price cart-item-total ${item.custom_price ? 'text-info' : ''}">
                    ${cs}${item.price.toFixed(2)} × ${item.quantity} = ${cs}${(item.price * item.quantity).toFixed(2)}
                    ${item.custom_price ? '<small class="d-block text-muted">Custom Price</small>' : ''}
                </div>
            </div>
            <div class="qty-control">
                <button type="button" class="qty-btn" data-cart-index="${index}" data-cart-action="decrease">−</button>
                <input type="text" class="qty-input" style="width: 60px;" data-cart-index="${index}" data-cart-qty value="${item.quantity}" min="1" max="${item.max_qty}">
                <button type="button" class="qty-btn" data-cart-index="${index}" data-cart-action="increase">+</button>
            </div>
            <button type="button" class="btn btn-sm btn-link text-danger" data-cart-index="${index}" data-cart-action="remove" title="Remove">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `).join('');
    
    updateTotals();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateQuantity(index, delta) {
    const currentItems = getCurrentCartItems();
    if (index < 0 || index >= currentItems.length) return;
    const item = currentItems[index];
    const newQty = item.quantity + delta;
    
    if (newQty >= 1 && newQty <= item.max_qty) {
        item.quantity = newQty;
        setCurrentCartItems(currentItems);
        renderCart();
    }
}

function setQuantity(index, value) {
    const currentItems = getCurrentCartItems();
    if (index < 0 || index >= currentItems.length) return;
    const item = currentItems[index];
    const qty = parseInt(value, 10);
    
    if (!isNaN(qty) && qty >= 1 && qty <= item.max_qty) {
        item.quantity = qty;
        setCurrentCartItems(currentItems);
        renderCart();
    }
}

function removeFromCart(index) {
    const currentItems = getCurrentCartItems();
    if (index < 0 || index >= currentItems.length) return;
    currentItems.splice(index, 1);
    setCurrentCartItems(currentItems);
    renderCart();
}

function clearCart() {
    const currentItems = getCurrentCartItems();
    if (currentItems.length > 0 && confirm('Clear all items from current cart?')) {
        setCurrentCartItems([]);
        renderCart();
    }
}

function updateTotals() {
    const currentItems = getCurrentCartItems();
    const subtotal = currentItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountEl = document.getElementById('discount');
    const taxEl = document.getElementById('tax');
    const discount = discountEl ? (parseFloat(discountEl.value) || 0) : 0;
    const tax = taxEl ? (parseFloat(taxEl.value) || 0) : 0;
    const total = subtotal - discount + tax;
    
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    if (subtotalEl) subtotalEl.textContent = cs + subtotal.toFixed(2);
    if (totalEl) totalEl.textContent = cs + total.toFixed(2);
}

function parseTotalText(el) {
    if (!el) return 0;
    const text = (el.textContent || '').replace(/^[^\d.-]+/, '').trim();
    return parseFloat(text) || 0;
}

function openCheckoutModal() {
    const total = parseTotalText(document.getElementById('total'));
    document.getElementById('modalTotal').value = cs + total.toFixed(2);
    document.getElementById('paidAmount').value = total.toFixed(2);
    calculateChange();
    
    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
}

function calculateChange() {
    const total = parseTotalText(document.getElementById('total'));
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    const change = paid - total;
    const due = Math.abs(Math.min(0, change));
    
    document.getElementById('changeAmount').textContent = cs + Math.max(0, change).toFixed(2);
    document.getElementById('changeAmount').className = change >= 0 ? 'mb-0 text-success' : 'mb-0 text-muted';
    
    document.getElementById('dueAmount').textContent = cs + due.toFixed(2);
    document.getElementById('dueAmount').className = due > 0 ? 'mb-0 text-danger' : 'mb-0 text-muted';
}

function completeSale() {
    const currentItems = getCurrentCartItems();
    const total = parseTotalText(document.getElementById('total'));
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    
    if (paid <= 0) {
        alert('Paid amount must be greater than 0');
        return;
    }
    
    const data = {
        items: currentItems.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            discount: item.discount,
            custom_price: item.custom_price || null
        })),
        sale_date: (function () {
            const el = document.getElementById('saleDate');
            return (el && el.value) ? el.value : null;
        })(),
        customer_name: document.getElementById('customerName').value,
        customer_phone: document.getElementById('customerPhone').value,
        discount: parseFloat(document.getElementById('discount').value) || 0,
        tax: parseFloat(document.getElementById('tax').value) || 0,
        paid_amount: paid,
        payment_method: document.querySelector('input[name="paymentMethod"]:checked').value,
        notes: document.getElementById('saleNotes').value
    };
    
    fetch('{{ route("pos.checkout") }}', {
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
            // Clear current cart after successful sale
            setCurrentCartItems([]);
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
@endpush
