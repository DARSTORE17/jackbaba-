@extends('layouts.customer')

@section('title', 'My Wishlist - Bravus Market')

@section('styles')
<style>
    .wishlist-container {
        max-width: 980px;
        margin: 0 auto;
        padding: 16px;
    }

    .wishlist-header {
        text-align: center;
        margin-bottom: 22px;
    }

    .wishlist-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2563EB;
        margin-bottom: 10px;
    }

    .wishlist-subtitle {
        font-size: 0.92rem;
        color: #6b7280;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .wishlist-item {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .wishlist-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .wishlist-image {
        position: relative;
        height: 105px;
        overflow: hidden;
    }

    .wishlist-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .wishlist-item:hover .wishlist-image img {
        transform: scale(1.05);
    }

    .wishlist-remove {
        position: absolute;
        top: 7px;
        right: 7px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .wishlist-remove:hover {
        background: #ef4444;
        transform: scale(1.1);
    }

    .wishlist-content {
        padding: 10px;
    }

    .wishlist-product-title {
        font-size: 0.86rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 6px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .wishlist-price {
        font-size: 0.96rem;
        font-weight: 700;
        color: #2563EB;
        margin-bottom: 9px;
    }

    .wishlist-actions {
        display: flex;
        gap: 7px;
        flex-direction: column;
    }

    .btn-add-to-cart {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        border: none;
        border-radius: 7px;
        padding: 7px 9px;
        color: white;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
    }

    .btn-add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 111, 145, 0.4);
        background: linear-gradient(135deg, #1D4ED8, #2563EB);
    }

    .btn-view-product {
        background: white;
        border: 2px solid #2563EB;
        color: #2563EB;
        border-radius: 7px;
        padding: 6px 9px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-view-product:hover {
        background: #2563EB;
        color: white;
        transform: translateY(-2px);
    }

    .empty-wishlist {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .empty-wishlist i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-wishlist h3 {
        color: #64748b;
        margin-bottom: 10px;
    }

    .empty-wishlist p {
        color: #94a3b8;
        margin-bottom: 30px;
    }

    .btn-shop-now {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        border: none;
        border-radius: 25px;
        padding: 12px 30px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-shop-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 111, 145, 0.4);
        background: linear-gradient(135deg, #1D4ED8, #2563EB);
        color: white;
    }

    /* Loading spinner for buttons */
    .wishlist-remove .spinner-border,
    .btn-add-to-cart .spinner-border {
        width: 16px;
        height: 16px;
    }

    .wishlist-remove.d-none .spinner-border,
    .btn-add-to-cart.d-none .spinner-border {
        display: none;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .wishlist-container {
            padding: 15px;
        }

        .wishlist-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .wishlist-title {
            font-size: 1.55rem;
        }

        .wishlist-actions {
            flex-direction: column;
        }

        .btn-add-to-cart,
        .btn-view-product {
            padding: 7px 8px;
            font-size: 0.74rem;
        }
    }

    @media (max-width: 576px) {
        .wishlist-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wishlist-title {
            font-size: 1.35rem;
        }
    }
</style>
@endsection

@section('content')
<main class="wishlist-container">
    <div class="wishlist-header">
        <h1 class="wishlist-title">
            <i class="bi bi-heart-fill me-2"></i>My Wishlist
        </h1>
        <p class="wishlist-subtitle">Products you've saved for later</p>
    </div>

    @if($wishlistItems->count() > 0)
        <div class="wishlist-grid">
            @foreach($wishlistItems as $item)
                <div class="wishlist-item" data-wishlist-id="{{ $item->id }}">
                    <div class="wishlist-image">
                        <img src="{{ $item->product->thumbnail
                            ? asset('storage/' . $item->product->thumbnail)
                            : ($item->product->media->where('is_primary', true)->first()
                                ? asset('storage/' . $item->product->media->where('is_primary', true)->first()->file_path)
                                : asset('img/logo.png')) }}"
                            alt="{{ $item->product->name }}">

                        <button class="wishlist-remove" onclick="removeFromWishlist({{ $item->id }})" title="Remove from wishlist">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>

                    <div class="wishlist-content">
                        <h3 class="wishlist-product-title">
                            <a href="{{ route('shop.show', $item->product->slug) }}" class="text-decoration-none text-dark">
                                {{ $item->product->name }}
                            </a>
                        </h3>

                        <div class="wishlist-price">Tsh{{ number_format($item->product->new_price, 2) }}</div>

                        <div class="wishlist-actions">
                            <button class="btn-add-to-cart" onclick="addToCartFromWishlist({{ $item->product->id }}, '{{ $item->product->name }}')">
                                <i class="bi bi-cart-plus"></i>
                                Add to Cart
                            </button>

                            <a href="{{ route('shop.show', $item->product->slug) }}" class="btn-view-product">
                                <i class="bi bi-eye"></i>
                                View Product
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-wishlist">
            <i class="bi bi-heart"></i>
            <h3>Your wishlist is empty</h3>
            <p>Start saving products you love for later!</p>
            <a href="{{ route('shop') }}" class="btn-shop-now">
                <i class="bi bi-shop"></i>
                Start Shopping
            </a>
        </div>
    @endif
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove item from wishlist
    window.removeFromWishlist = function(wishlistId) {
        const wishlistItem = document.querySelector(`[data-wishlist-id="${wishlistId}"]`);
        const removeBtn = wishlistItem.querySelector('.wishlist-remove');

        // Show loading spinner
        removeBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        removeBtn.disabled = true;

        fetch(`/wishlist/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from DOM with animation
                wishlistItem.style.transition = 'all 0.3s ease';
                wishlistItem.style.opacity = '0';
                wishlistItem.style.transform = 'scale(0.9)';

                setTimeout(() => {
                    wishlistItem.remove();

                    // Check if wishlist is now empty
                    const remainingItems = document.querySelectorAll('.wishlist-item');
                    if (remainingItems.length === 0) {
                        // Reload page to show empty state
                        location.reload();
                    } else {
                        // Show success message
                        showNotification('Product removed from wishlist', 'success');
                    }
                }, 300);
            } else {
                showNotification(data.message || 'Failed to remove item', 'error');
                // Reset button
                removeBtn.innerHTML = '<i class="bi bi-x"></i>';
                removeBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
            // Reset button
            removeBtn.innerHTML = '<i class="bi bi-x"></i>';
            removeBtn.disabled = false;
        });
    };

    // Add to cart from wishlist
    window.addToCartFromWishlist = function(productId, productName) {
        // Check authentication first (check for logout form or logout link)
        const isLoggedIn = document.querySelector('form[action*="logout"]') ||
                          document.querySelector('a[href*="logout"]') ||
                          document.querySelector('meta[name="user-authenticated"]');

        if (!isLoggedIn) {
            Swal.fire({
                title: 'Login Required',
                text: 'Please login to add items to your cart',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Login',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
            return;
        }

        // Find the button and show loading
        const wishlistItem = event.target.closest('.wishlist-item');
        const addToCartBtn = wishlistItem.querySelector('.btn-add-to-cart');
        const originalText = addToCartBtn.innerHTML;

        addToCartBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
        addToCartBtn.disabled = true;

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', '1');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/cart/add', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.status === 419) {
                window.location.reload();
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                // Show success with heart animation
                Swal.fire({
                    title: 'Added to Cart!',
                    text: productName + ' has been added to your cart',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'animate__animated animate__heartBeat'
                    }
                });

                // Update cart count if element exists
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(element => {
                    if (element) {
                        element.textContent = data.cart_count;
                        element.style.display = 'inline';
                    }
                });

                // Reset button
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = false;
            } else if (data && data.already_in_cart) {
                // Product already in cart
                Swal.fire({
                    title: 'Already in Cart',
                    text: productName + ' is already in your cart',
                    icon: 'info',
                    confirmButtonColor: '#2563EB',
                    confirmButtonText: 'View Cart'
                }).then(() => {
                    window.location.href = '/cart';
                });

                // Reset button
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = false;
            } else {
                showNotification(data ? data.message : 'Failed to add to cart', 'error');
                // Reset button
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
            // Reset button
            addToCartBtn.innerHTML = originalText;
            addToCartBtn.disabled = false;
        });
    };

    // Notification helper
    function showNotification(message, type) {
        // Remove existing notifications
        const existing = document.querySelectorAll('.toast-notification');
        existing.forEach(toast => toast.remove());

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `toast-notification alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; border-radius: 10px;';
        notification.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
});
</script>
@endsection
