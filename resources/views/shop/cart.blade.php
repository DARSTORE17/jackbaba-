@extends('layouts.app')

@section('title', 'Shopping Cart - KidsStore')

@section('css')
<style>
    /* Full page height */
    html, body {
        height: 100%;
    }

    .shop-container {
        margin: 0 auto;
        padding: 15px;
        min-height: calc(100vh - 150px); /* Adjust based on your header/footer height */
    }

    /* Make cart items container full height with vertical scroll */
    .cart-items-container {
        max-height: calc(100vh - 250px);
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* 2 columns layout for cart items */
    .cart-item-col {
        width: 50%;
        padding: 10px;
    }

    /* Cart item styling */
    .cart-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .cart-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .cart-item-col {
            width: 100%;
        }
        
        .cart-items-container {
            max-height: calc(100vh - 300px);
        }
    }

    @media (min-width: 769px) and (max-width: 992px) {
        .cart-item-col {
            width: 50%;
        }
    }

    /* Custom scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #FF6F91;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #ff4d7c;
    }

    /* Ensure full width for row */
    .cart-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
        width: 100%;
    }

    /* Image styling */
    .cart-item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Touch-friendly buttons */
    .quantity-btn, .action-btn {
        min-width: 40px;
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .quantity-input {
        min-width: 60px;
        text-align: center;
        font-weight: bold;
    }

    /* Improved button styling for small screens */
    @media (max-width: 768px) {
        .quantity-btn, .action-btn {
            min-width: 48px;
            min-height: 48px;
            padding: 12px;
            font-size: 16px;
        }

        .quantity-input {
            min-width: 70px;
            font-size: 16px;
            height: 40px;
        }

        /* Stack cart action buttons on small screens */
        .cart-actions {
            flex-direction: column !important;
            gap: 10px;
        }

        .cart-actions button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }

        /* Make remove buttons more prominent on mobile */
        .action-btn {
            min-width: 40px;
            min-height: 40px;
            border: none;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* Minimize price and discount elements on mobile */
        .card-text.text-muted.fw-bold,
        .fw-bold.text-primary {
            font-size: 14px;
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Ensure discount text fits within price container */
        .card-text.text-muted.fw-bold {
            margin-bottom: 0.5rem;
        }

        /* Adjust cart item layout for better mobile fit */
        .cart-item .col-8 {
            padding-left: 8px;
        }

        /* Make quantity controls more compact on mobile */
        .d-flex.align-items-center.mb-3 {
            margin-bottom: 0.5rem !important;
        }

        /* Ensure totals and remove button fit on same line */
        .d-flex.justify-content-between.align-items-center {
            flex-wrap: wrap;
            gap: 5px;
        }

        .d-flex.justify-content-between.align-items-center .fw-bold.text-primary {
            flex-shrink: 0;
        }
    }
</style>
@endsection

@section('content')
    <main class="shop-container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2 mb-0 text-primary">Shopping Cart</h1>
                    @if($cartItems->count() > 0)
                        <a href="{{ route('shop') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Continue Shopping
                        </a>
                    @endif
                </div>

                @if($cartItems->count() > 0)
                    <div class="row">
                        <!-- Cart Items -->
                        <div class="col-12 col-lg-8">
                            <div class="cart-items-container">
                                <div class="card shadow-sm border-primary h-100">
                                    <div class="card-body p-0">
                                        <div class="row">
                                            @foreach($cartItems as $item)
                                                <div class="col-12 col-lg-6 mb-3">
                                                    <div class="card h-100" data-cart-item-id="{{ $item->id }}" data-product-id="{{ $item->product_id }}" data-price="{{ $item->price }}" data-stock="{{ $item->product->stock }}">
                                                        <div class="card-body">
                                                            <div class="row g-2">
                                                                <!-- Product Image -->
                                                                <div class="col-4">
                                                                    <img src="{{ $item->product->thumbnail
                                                                        ? asset('storage/' . $item->product->thumbnail)
                                                                        : ($item->product->media->where('is_primary', true)->first()
                                                                            ? asset('storage/' . $item->product->media->where('is_primary', true)->first()->file_path)
                                                                            : asset('img/logo.png')) }}"
                                                                        alt="{{ $item->product->name }}"
                                                                        class="img-fluid rounded">
                                                                </div>

                                                                <!-- Product Details -->
                                                                <div class="col-8">
                                                                    <h6 class="card-title mb-1 text-truncate">
                                                                        <a href="{{ route('shop.show', $item->product->slug) }}" class="text-decoration-none text-dark">
                                                                            {{ $item->product->name }}
                                                                        </a>
                                                                    </h6>
                                                                    <p class="card-text text-muted fw-bold">Tsh{{ number_format($item->price, 2) }}</p>

                                                                    <!-- Quantity Controls -->
                                                                    <div class="d-flex align-items-center mb-3">
                                                                        <button class="btn btn-sm btn-outline-primary quantity-btn" style="border-radius: 15px" onclick="updateQuantity({{ $item->id }}, -1)">
                                                                            <i class="bi bi-dash"></i>
                                                                        </button>
                                                                        <input type="number" min="1" max="{{ $item->product->stock }}" value="{{ $item->quantity }}"
                                                                               class="form-control form-control-sm quantity-input mx-2" style="border-radius: 10px"
                                                                               onchange="validateAndUpdateQuantity(this, {{ $item->id }}, {{ $item->product->stock }})">
                                                                        <button class="btn btn-sm btn-outline-primary quantity-btn" style="border-radius: 15px" onclick="updateQuantity({{ $item->id }}, 1)">
                                                                            <i class="bi bi-plus"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Total and Remove -->
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="fw-bold text-primary">Tsh{{ number_format($item->price * $item->quantity, 2) }}</div>
                                                                        <button class="btn btn-sm btn-outline-danger action-btn" onclick="removeItem({{ $item->id }})" data-item-id="{{ $item->id }}">
                                                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                            <span class="button-text">
                                                                                <i class="bi bi-trash"></i> Remove
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Cart Actions -->
                                <div class="d-flex justify-content-between mt-3 cart-actions" style="padding: 0 16px;">
                                    <button class="btn btn-outline-danger btn-lg rounded-pill shadow-sm" onclick="clearCart()" id="clearCartBtn" style="border-radius: 15px;">
                                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                        <span class="button-text">
                                            <i class="bi bi-trash me-2"></i> Clear Cart
                                        </span>
                                    </button>
                                    <div>
                                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg rounded-pill shadow-sm" style="border-radius: 15px;">
                                            <i class="bi bi-credit-card me-2"></i> Proceed to Checkout
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                            <div class="sticky-top" style="top: 20px;">
                                <div class="card shadow-sm border-primary">
                                    <div class="card-header bg-white">
                                        <h5 class="mb-0">Order Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="cart-summary-subtotal">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                                            <span class="cart-summary-subtotal-amount">Tsh{{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax (18% VAT)</span>
                                            <span class="cart-summary-tax">Tsh{{ number_format($taxAmount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Shipping</span>
                                            <span class="cart-summary-shipping">{{ $shippingCost > 0 ? 'Tsh' . number_format($shippingCost, 2) : 'Free' }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold fs-5">
                                            <span>Total</span>
                                            <span class="text-primary cart-summary-total">Tsh{{ number_format($total, 2) }}</span>
                                        </div>

                                        <div class="alert alert-success mt-3 py-2 mb-0" style="display: none;">
                                            <small><i class="bi bi-check-circle"></i> You've qualified for FREE shipping!</small>
                                        </div>
                                        <div class="alert alert-info mt-3 py-2 mb-0" style="display: none;">
                                            <small>Add Tsh5,000.00 more for FREE shipping</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="text-center py-5" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
                        <div>
                            <div class="mb-4">
                                <i class="bi bi-cart-x display-1 text-muted"></i>
                            </div>
                            <h3 class="text-muted">Your cart is empty</h3>
                            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-shop"></i> Start Shopping
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        // Touch-friendly quantity updates
        function updateQuantity(cartItemId, change) {
            const cartItem = event.target.closest('.card');
            const input = cartItem.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const newValue = currentValue + change;

            if (newValue >= 1 && newValue <= 99) {
                updateQuantityDirectly(cartItemId, newValue, event.target);
            }
        }

        function updateQuantityDirectly(cartItemId, quantity, buttonElement) {
            const cartItem = document.querySelector(`.card[data-cart-item-id="${cartItemId}"]`);
            const stock = parseInt(cartItem.dataset.stock);
            if (quantity > stock) {
                Swal.fire({
                    title: 'Stock Limit Reached!',
                    text: `Maximum stock available is ${stock}`,
                    icon: 'warning',
                    confirmButtonColor: '#FF6F91',
                    confirmButtonText: 'OK'
                });
                const input = cartItem.querySelector('.quantity-input');
                input.value = stock;
                return;
            }

            const button = buttonElement.closest('.quantity-btn');
            if (button) button.disabled = true;

            fetch(`/cart/update/${cartItemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI without refreshing
                    updateCartUI(cartItemId, quantity);
                    if (button) button.disabled = false;
                } else {
                    Swal.fire({
                        title: 'Update Failed',
                        text: data.message || 'Failed to update quantity',
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'OK'
                    });
                    if (button) button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'OK'
                });
                if (button) button.disabled = false;
            });
        }

        function validateAndUpdateQuantity(input, cartItemId, stock) {
            let value = parseInt(input.value);
            if (isNaN(value) || value < 1) {
                value = 1;
                input.value = value;
            } else if (value > stock) {
                value = stock;
                input.value = value;
                Swal.fire({
                    title: 'Stock Limit Reached!',
                    text: `Maximum stock available is ${stock}`,
                    icon: 'warning',
                    confirmButtonColor: '#FF6F91',
                    confirmButtonText: 'OK'
                });
            }
            updateQuantityDirectly(cartItemId, value);
        }

        function updateCartUI(updatedItemId, newQuantity) {
            // Update the quantity input
            const cartItem = document.querySelector(`.card[data-cart-item-id="${updatedItemId}"]`);
            if (cartItem) {
                const quantityInput = cartItem.querySelector('.quantity-input');
                quantityInput.value = newQuantity;

                // Update item total
                const price = parseFloat(cartItem.dataset.price);
                const newTotal = price * newQuantity;
                cartItem.querySelector('.fw-bold.text-primary').textContent = 'Tsh' + newTotal.toFixed(2);

                // Recalculate totals
                calculateTotals();
            }
        }

        function removeItem(cartItemId) {
            Swal.fire({
                title: 'Remove Item?',
                text: 'Are you sure you want to remove this item from your cart?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading spinner
                    const removeBtn = document.querySelector(`button[data-item-id="${cartItemId}"]`);
                    if (removeBtn) {
                        removeBtn.disabled = true;
                        removeBtn.querySelector('.spinner-border').classList.remove('d-none');
                        removeBtn.querySelector('.button-text').style.display = 'none';
                    }

                    fetch(`/cart/remove/${cartItemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove item from UI
                            const cartItem = document.querySelector(`.card[data-cart-item-id="${cartItemId}"]`);
                            if (cartItem) {
                                cartItem.parentElement.remove();
                            }

                            // Check if cart is now empty
                            const remainingItems = document.querySelectorAll('.card[data-product-id]');
                            if (remainingItems.length === 0) {
                                // Cart is empty, reload to show empty cart state
                                Swal.fire({
                                    title: 'Cart is Empty!',
                                    text: 'All items have been removed from your cart.',
                                    icon: 'info',
                                    confirmButtonColor: '#FF6F91',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                // Cart still has items, just recalculate totals
                                calculateTotals();
                                Swal.fire({
                                    title: 'Removed!',
                                    text: 'Item has been removed from your cart.',
                                    icon: 'success',
                                    confirmButtonColor: '#10b981',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        } else {
                            Swal.fire({
                                title: 'Failed to Remove',
                                text: data.message || 'Failed to remove item from cart',
                                icon: 'error',
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while removing the item.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'OK'
                        });
                    })
                    .finally(() => {
                        // Hide loading spinner
                        const removeBtn = document.querySelector(`button[data-item-id="${cartItemId}"]`);
                        if (removeBtn) {
                            removeBtn.disabled = false;
                            removeBtn.querySelector('.spinner-border').classList.add('d-none');
                            removeBtn.querySelector('.button-text').style.display = 'inline';
                        }
                    });
                }
            });
        }

        function clearCart() {
            Swal.fire({
                title: 'Clear Entire Cart?',
                text: 'Are you sure you want to remove all items from your cart? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Clear All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading spinner
                    const clearBtn = document.getElementById('clearCartBtn');
                    if (clearBtn) {
                        clearBtn.disabled = true;
                        clearBtn.querySelector('.spinner-border').classList.remove('d-none');
                        clearBtn.querySelector('.button-text').style.display = 'none';
                    }

                    fetch('/cart/clear', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Cart Cleared!',
                                text: 'All items have been removed from your cart.',
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                timer: 2500,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the page to show empty cart
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Failed to Clear Cart',
                                text: data.message || 'Failed to clear cart',
                                icon: 'error',
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while clearing your cart.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'OK'
                        });
                    })
                    .finally(() => {
                        // Hide loading spinner
                        const clearBtn = document.getElementById('clearCartBtn');
                        if (clearBtn) {
                            clearBtn.disabled = false;
                            clearBtn.querySelector('.spinner-border').classList.add('d-none');
                            clearBtn.querySelector('.button-text').style.display = 'inline';
                        }
                    });
                }
            });
        }

        function calculateTotals() {
            let subtotal = 0;
            const cartItems = document.querySelectorAll('.card[data-product-id]');
            let itemCount = 0;

            cartItems.forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                const price = parseFloat(item.dataset.price);
                subtotal += price * quantity;
                itemCount += quantity;
            });

            const taxAmount = subtotal * 0.18;
            const shippingCost = subtotal >= 100000 ? 0 : 5000; // Assuming free shipping at 100000
            const total = subtotal + taxAmount + shippingCost;

            // Update UI with null checks (cart summary elements don't exist when cart is empty)
            const subtotalElement = document.querySelector('.cart-summary-subtotal');
            const subtotalAmountElement = document.querySelector('.cart-summary-subtotal-amount');
            const taxElement = document.querySelector('.cart-summary-tax');
            const shippingElement = document.querySelector('.cart-summary-shipping');
            const totalElement = document.querySelector('.cart-summary-total');

            if (subtotalElement) subtotalElement.textContent = 'Subtotal (' + itemCount + ' items)';
            if (subtotalAmountElement) subtotalAmountElement.textContent = 'Tsh' + subtotal.toFixed(2);
            if (taxElement) taxElement.textContent = 'Tsh' + taxAmount.toFixed(2);
            if (shippingElement) shippingElement.textContent = shippingCost > 0 ? 'Tsh' + shippingCost.toFixed(2) : 'Free';
            if (totalElement) totalElement.textContent = 'Tsh' + total.toFixed(2);

            // Update alerts based on shipping
            const freeShippingAlert = document.querySelector('.alert-success');
            const addMoreAlert = document.querySelector('.alert-info');
            if (shippingCost === 0) {
                if (freeShippingAlert) freeShippingAlert.style.display = 'block';
                if (addMoreAlert) addMoreAlert.style.display = 'none';
            } else {
                if (freeShippingAlert) freeShippingAlert.style.display = 'none';
                if (addMoreAlert) {
                    addMoreAlert.style.display = 'block';
                    const moreNeeded = 100000 - subtotal;
                    if (addMoreAlert.querySelector('small')) {
                        addMoreAlert.querySelector('small').textContent = 'Add Tsh' + moreNeeded.toFixed(2) + ' more for FREE shipping';
                    }
                }
            }
        }

        // Make buttons more touch-friendly
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.quantity-btn, .action-btn');
            buttons.forEach(button => {
                button.style.cursor = 'pointer';
                button.addEventListener('touchstart', function() {
                    this.style.opacity = '0.7';
                });
                button.addEventListener('touchend', function() {
                    this.style.opacity = '1';
                });
            });

            // Calculate totals on page load
            calculateTotals();
        });
    </script>
@endsection
