@extends('layouts.app')

@section('title', $product->name . ' - KidsStore')
@section('css')
    <style>
        :root {
            --primary-color: #FF6F91;
            --secondary-color: #764ba2;
            --accent-color: #667eea;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8f9fa;
            --dark-bg: #1f2937;
            --text-dark: #1f2937;
            --text-light: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        .product-details {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #6b7280;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: '>';
            margin: 0 12px;
            color: #9ca3af;
            font-weight: 400;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: #e63869;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: var(--text-dark);
            font-weight: 600;
        }

        .product-gallery {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-bottom: 30px;
        }

        .gallery-main-row {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .gallery-top-row {
            display: flex;
            gap: 0;
            align-items: flex-start;
        }

        .gallery-sidebar {
            width: 120px;
            flex-shrink: 0;
            height: 100%;
            align-self: stretch;
        }

        .gallery-thumbs-vertical {
            display: flex;
            flex-direction: column;
            gap: 10px;
            height: 400px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
            padding-right: 4px;
            flex-shrink: 0;
        }

        .gallery-thumbs-vertical::-webkit-scrollbar {
            width: 4px;
        }

        .gallery-thumbs-vertical::-webkit-scrollbar-track {
            background: transparent;
        }

        .gallery-thumbs-vertical::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .thumb-image {
            flex: 0 0 70px;
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        /* WRAPPER YA CHINI – ONDOA WHITE SPACE */
        .horizontal-thumbs-container {
            margin-top: 4px;
            /* distance ndogo sana kutoka main image */
            padding: 0;
            line-height: 0;
            /* izuie extra height ya line box */
        }

        .gallery-thumbs-horizontal {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 0;
            margin: 0;
            height: 70px;
            /* kimo cha strip ya thumbnails + scrollbar */
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        .gallery-thumbs-horizontal::-webkit-scrollbar {
            height: 4px;
        }

        .gallery-thumbs-horizontal::-webkit-scrollbar-track {
            background: transparent;
        }

        .gallery-thumbs-horizontal::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .thumb-image.active,
        .thumb-image:hover,
        .thumb-image-horizontal.active,
        .thumb-image-horizontal:hover {
            border-color: var(--primary-color);
        }

        .thumb-image-horizontal img {
            width: 70px;
            height: 60px;
            object-fit: contain;
            display: block;
        }

        .thumb-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .main-image {
            border-radius: 15px;
            overflow: hidden;
            background: var(--light-bg);
            width: 100%;
            height: 330px;
            box-shadow: var(--shadow-lg);
            margin-top: 0;
            /* hakuna nafasi kati ya sidebar na image */
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }


        .product-info {
            margin-bottom: 40px;
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .star {
            font-size: 18px;
            color: #fbbf24;
        }

        .rating-text {
            color: #6b7280;
            font-size: 14px;
        }

        .product-prices {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .current-price {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .old-price {
            font-size: 1.5rem;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .discount-badge {
            background: var(--danger-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .stock-info {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 25px;
        }

        .stock-in {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
        }

        .stock-low {
            background: rgba(251, 191, 36, 0.1);
            color: var(--warning-color);
        }

        .stock-out {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .btn-add-cart,
        .btn-wishlist {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add-cart {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-add-cart:hover {
            background: #e63869;
            transform: translateY(-2px);
        }

        .btn-wishlist {
            background: white;
            color: var(--text-dark);
            border: 2px solid #e5e7eb;
        }

        .btn-wishlist:hover {
            background: var(--light-bg);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-wishlist.in-wishlist {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-wishlist.in-wishlist:hover {
            background: #e63869;
            border-color: #e63869;
            color: white;
        }

        .btn-wishlist.in-wishlist i {
            color: white;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .quantity-input {
            display: flex;
            align-items: center;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .quantity-btn:hover {
            background: #e9ecef;
        }

        .quantity-input input {
            border: none;
            width: 60px;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
        }

        .product-tabs {
            margin-bottom: 40px;
        }

        .tab-buttons {
            display: flex;
            gap: 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .tab-btn {
            padding: 15px 25px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tab-content h4 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .specifications-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .related-products {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid #e5e7eb;
        }

        .related-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 30px;
        }

        .related-grid {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 10px 0;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        .related-grid::-webkit-scrollbar {
            height: 4px;
        }

        .related-grid::-webkit-scrollbar-track {
            background: transparent;
        }

        .related-grid::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .related-grid::-webkit-scrollbar-thumb:hover {
            background-color: #e63869;
        }

        .related-card {
            flex: 0 0 250px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .related-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
        }

        .related-image {
            aspect-ratio: 4/3;
            overflow: hidden;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .related-card:hover .related-image img {
            transform: scale(1.05);
        }

        .related-info {
            padding: 15px;
        }

        .related-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .related-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        @media (max-width: 767.98px) {
            .product-details {
                padding: 15px;
            }

            .product-gallery {
                margin-bottom: 20px;
            }

            .gallery-top-row {
                flex-direction: column;
                gap: 0;
            }

            .gallery-sidebar {
                display: none;
            }

            .main-image {
                order: 1;
                aspect-ratio: 4/3;
                margin-bottom: 0;
                /* bado hakuna gap */
            }

            .horizontal-thumbs-container {
                display: block;
            }

            .gallery-thumbs-horizontal {
                display: flex;
                gap: 8px;
                overflow-x: auto;
                overflow-y: hidden;
                padding: 4px 0;
                margin: 0;
                height: 70px;
                scrollbar-width: thin;
                scrollbar-color: var(--primary-color) transparent;
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }

            .thumb-image-horizontal {
                flex: 0 0 70px;
                height: 60px;
                border-radius: 10px;
                overflow: hidden;
                cursor: pointer;
                border: 3px solid transparent;
                transition: all 0.3s ease;
            }

            .thumb-image-horizontal.active,
            .thumb-image-horizontal:hover {
                border-color: var(--primary-color);
            }

            .product-title {
                font-size: 2rem;
            }

            .current-price {
                font-size: 1.8rem;
            }

            .quantity-selector {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-buttons {
                flex-direction: column;
            }

            .specifications-list {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 768px) {
            .thumb-image {
                aspect-ratio: 1;
            }

            .gallery-thumbs-horizontal {
                display: none;
            }
        }
    </style>
@endsection


@section('content')
    <div class="product-details">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('shop') }}" class="text-decoration-none">Shop</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('shop') }}?category={{ $product->category_id }}" class="text-decoration-none">
                        Shoes
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Air Force Shoe</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-6">
                <!-- Product Gallery -->
                <div class="product-gallery">
                    <!-- Top row: Main image and left horizontal thumbnails -->
                    <div class="gallery-top-row">
                        <div class="gallery-sidebar">
                            <div class="gallery-thumbs-vertical">
                                @php
                                    $images = $product->media->where('type', 'image') ?: collect();
                                    $primaryImage = $images->where('is_primary', true)->first() ?: $images->first();
                                @endphp

                                @if ($primaryImage)
                                    @foreach ($images as $image)
                                        <div class="thumb-image {{ $loop->first ? 'active' : '' }}"
                                            onclick="changeImage('{{ asset('storage/' . $image->file_path) }}', this)">
                                            <img src="{{ asset('storage/' . $image->file_path) }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="main-image">
                            @php
                                $mainImage = $primaryImage
                                    ? asset('storage/' . $primaryImage->file_path)
                                    : asset('img/logo.png');
                            @endphp
                            <img id="mainImage" src="{{ $mainImage }}" alt="{{ $product->name }}">
                        </div>
                    </div>

                    <!-- Bottom row: Horizontal thumbnails -->
                    @if ($primaryImage && $images->count() > 1)
                        <div class="horizontal-thumbs-container"> <!-- Add this wrapper -->
                            <div class="gallery-thumbs-horizontal">
                                @foreach ($images as $image)
                                    <div class="thumb-image-horizontal {{ $loop->first ? 'active' : '' }}"
                                        onclick="changeImage('{{ asset('storage/' . $image->file_path) }}', this)">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Product Info -->
                <div class="product-info">
                    <h1 class="product-title">{{ $product->name }}</h1>

                    @if ($product->rate > 0)
                        <div class="product-rating">
                            <div class="stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $product->rate ? 'bi-star-fill' : 'bi-star' }} star"></i>
                                @endfor
                            </div>
                            <span class="rating-text">{{ $product->rate }} ({{ number_format(rand(10, 500)) }}
                                reviews)</span>
                        </div>
                    @endif

                    <div class="product-prices">
                        <span class="current-price">Tsh{{ number_format($product->new_price, 2) }}</span>
                        @if ($product->old_price && $product->old_price > $product->new_price)
                            <span class="old-price">Tsh{{ number_format($product->old_price, 2) }}</span>
                            <span class="discount-badge">{{ $product->discount }}% OFF</span>
                        @endif
                    </div>

                    <div
                        class="stock-info {{ $product->stock > 10 ? 'stock-in' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                        <i class="bi bi-circle-fill"></i>
                        @if ($product->stock > 10)
                            <span>In Stock ({{ $product->stock }} available)</span>
                        @elseif($product->stock > 0)
                            <span>Only {{ $product->stock }} left in stock</span>
                        @else
                            <span>Out of Stock</span>
                        @endif
                    </div>

                    <div class="quantity-selector">
                        <label>Quantity:</label>
                        <div class="quantity-input">
                            <button class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantityInput" value="1" min="1"
                                max="{{ $product->stock }}" readonly>
                            <button class="quantity-btn" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-add-cart"
                            onclick="addToCart({{ $product->id }}, document.getElementById('quantityInput').value)">
                            <i class="bi bi-cart-plus"></i>
                            Add to Cart
                        </button>
                        <button class="btn-wishlist" id="wishlistBtn" onclick="toggleWishlist({{ $product->id }})">
                            <i class="bi bi-heart" id="wishlistIcon"></i>
                            <span id="wishlistText">Add to Wishlist</span>
                        </button>
                    </div>

                    <!-- Share buttons -->
                    <div class="share-section">
                        <strong>Share:</strong>
                        <div class="mt-2">
                            <a href="#" class="text-decoration-none me-3">
                                <i class="bi bi-facebook text-primary"></i>
                            </a>
                            <a href="#" class="text-decoration-none me-3">
                                <i class="bi bi-twitter text-info"></i>
                            </a>
                            <a href="#" class="text-decoration-none me-3">
                                <i class="bi bi-whatsapp text-success"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-link-45deg text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="switchTab('description')">Description</button>
                <button class="tab-btn" onclick="switchTab('specifications')">Specifications</button>
                <button class="tab-btn" onclick="switchTab('reviews')">Reviews</button>
            </div>

            <div id="description" class="tab-content active">
                <h4>About this product</h4>
                <div class="prose">
                    {!! $product->description->description ?? '<p>No description available.</p>' !!}
                    {!! $product->description->details ?? '' !!}
                </div>
            </div>

            <div id="specifications" class="tab-content">
                <h4>Technical Specifications</h4>
                @if ($product->description && $product->description->specifications)
                    <div class="specifications-list">
                        @php
                            $specs = json_decode($product->description->specifications, true);
                            if (is_array($specs)) {
                                foreach ($specs as $key => $value) {
                                    echo '<div class="spec-item"><span><strong>' .
                                        htmlspecialchars($key) .
                                        ':</strong></span><span>' .
                                        htmlspecialchars($value) .
                                        '</span></div>';
                                }
                            } else {
                                echo '<div class="spec-item"><span>No specifications available</span></div>';
                            }
                        @endphp
                    </div>
                @else
                    <p>No specifications available.</p>
                @endif
            </div>

            <div id="reviews" class="tab-content">
                <h4>Customer Reviews</h4>
                <div class="text-center py-5">
                    <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Reviews feature coming soon!</p>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <section class="related-products">
                <h2 class="related-title">You might also like</h2>
                <div class="related-grid">
                    @foreach ($relatedProducts as $relatedProduct)
                        <a href="{{ route('shop.show', $relatedProduct->slug) }}" class="related-card">
                            <div class="related-image">
                                <img src="{{ $relatedProduct->thumbnail ? asset('storage/' . $relatedProduct->thumbnail) : asset('img/logo.png') }}"
                                    alt="{{ $relatedProduct->name }}">
                            </div>
                            <div class="related-info">
                                <h3 class="related-title">{{ $relatedProduct->name }}</h3>
                                <div class="related-price">Tsh{{ number_format($relatedProduct->new_price, 2) }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <script>
        // Helper function to show alerts
        function showAlert(type, message) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert-custom');
            existingAlerts.forEach(alert => alert.remove());

            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-custom alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            // Add to page
            document.body.appendChild(alertDiv);

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);

            // Add Bootstrap classes if available
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Alert(alertDiv);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Image gallery
            window.changeImage = function(src, element) {
                document.getElementById('mainImage').src = src;

                // Update active thumb - handle both layouts
                document.querySelectorAll('.thumb-image').forEach(thumb => {
                    thumb.classList.remove('active');
                });
                document.querySelectorAll('.thumb-image-horizontal').forEach(thumb => {
                    thumb.classList.remove('active');
                });
                element.classList.add('active');
            };

            // Quantity controls
            window.changeQuantity = function(change) {
                const input = document.getElementById('quantityInput');
                const currentValue = parseInt(input.value);
                const min = parseInt(input.min) || 1;
                const max = parseInt(input.max) || 99;

                let newValue = currentValue + change;
                if (newValue >= min && newValue <= max) {
                    input.value = newValue;
                    updateStockStatus(newValue, max);
                } else if (change > 0 && newValue > max) {
                    // Show stock limit notification when trying to exceed maximum stock
                    Swal.fire({
                        title: 'Stock Limit Reached!',
                        text: `Maximum stock available is ${max}`,
                        icon: 'warning',
                        confirmButtonColor: '#FF6F91',
                        confirmButtonText: 'OK'
                    });
                    input.value = max; // Set to maximum stock
                    updateStockStatus(max, max);
                }
            };

            // Function to update stock status based on selected quantity
            function updateStockStatus(selectedQuantity, totalStock) {
                const stockInfo = document.querySelector('.stock-info');
                const stockText = stockInfo.querySelector('span:last-child');
                const remainingStock = totalStock - selectedQuantity;

                // Remove existing stock classes
                stockInfo.classList.remove('stock-in', 'stock-low', 'stock-out');

                if (remainingStock > 10) {
                    stockInfo.classList.add('stock-in');
                    stockText.textContent = `In Stock (${remainingStock} available)`;
                } else if (remainingStock > 0) {
                    stockInfo.classList.add('stock-low');
                    stockText.textContent = `Only ${remainingStock} left in stock`;
                } else {
                    stockInfo.classList.add('stock-out');
                    stockText.textContent = 'Out of Stock';
                }
            }

            // Tab switching
            window.switchTab = function(tabId) {
                // Update tab buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.classList.add('active');

                // Update tab content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
            };

            // Add to cart functionality (now protected by auth middleware)
            window.addToCart = function(productId, quantity) {
                // Check if user is authenticated first (check for presence of login/logout elements)
                const isUserLoggedIn = document.querySelector('a[href*="login"]') === null &&
                                      document.querySelector('a[href*="logout"], form[action*="logout"]') !== null;

                if (!isUserLoggedIn) {
                    // User is not logged in - show notification with login/register options
                    Swal.fire({
                        title: 'Account Required',
                        html: 'Sorry Dear Customer! <br><br>' +
                              '<strong>You need an account to add items to your cart and complete purchases.</strong><br><br>' +
                              'If you already have an account, please login.<br>' +
                              'If you don\'t have an account yet, please register first.',
                        icon: 'info',
                        showCancelButton: true,
                        showCloseButton: true,
                        confirmButtonColor: '#007bff',
                        cancelButtonColor: '#28a745',
                        confirmButtonText: '<i class="bi bi-person-circle"></i> Login',
                        cancelButtonText: '<i class="bi bi-person-plus"></i> Register',
                        customClass: {
                            popup: 'swal-wide',
                            confirmButton: 'btn btn-primary me-5',
                            cancelButton: 'btn btn-success'
                        },
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Login button clicked
                            window.location.href = '/login';
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Register button clicked
                            window.location.href = '/register';
                        }
                        // If dismissed with close button or background click, stay on page
                    });
                    return; // Don't proceed with the request
                }

                // User is logged in - proceed with adding to cart
                // Prepare form data
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', quantity);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Disable button to prevent double clicks
                const button = event.target.closest('.btn-add-cart');
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';

                // Make AJAX request
                fetch('/cart/add', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {

                    if (response.status === 419) {
                        // CSRF token expired
                        Swal.fire({
                            title: 'Session Expired',
                            text: 'Please refresh the page and try again',
                            icon: 'warning',
                            confirmButtonColor: '#FF6F91',
                            confirmButtonText: 'Refresh Page'
                        }).then(() => {
                            window.location.reload();
                        });
                        return null;
                    }

                    return response.json();
                })
                .then(data => {
                    if (!data) return; // Skip if redirect was handled above

                    // Update cart count regardless of success or duplicate
                    try {
                        const cartCountElements = document.querySelectorAll('.cart-count');
                        cartCountElements.forEach(element => {
                            if (element) {
                                element.textContent = data.cart_count;
                                element.style.display = 'inline';
                            }
                        });
                    } catch (e) {
                        // Ignore if cart count elements don't exist
                    }

                    if (data.success) {
                        // Show success message for new item added
                        Swal.fire({
                            title: 'Item Added to Cart!',
                            text: data.message,
                            icon: 'success',
                            color: '#1f2937',
                            background: '#ffffff',
                            confirmButtonColor: '#FF6F91',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'View Cart',
                            showCancelButton: true,
                            cancelButtonText: 'Continue Shopping',
                            showCloseButton: true,
                            timer: 5000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'swal-wide',
                                confirmButton: 'btn btn-primary me-2',
                                cancelButton: 'btn btn-outline-secondary'
                            },
                            buttonsStyling: false,
                            didOpen: (popup) => {
                                // Custom progress bar color
                                const progressBar = popup.querySelector('.swal2-timer-progress-bar');
                                if (progressBar) {
                                    progressBar.style.setProperty('--swal2-timer-progress-bar-background', '#FF6F91');
                                }
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/cart';
                            }
                        });

                    } else if (data.already_in_cart) {
                        // Show info message for already in cart
                        Swal.fire({
                            title: 'Already in Cart',
                            text: data.message,
                            icon: 'info',
                            color: '#1f2937',
                            background: '#ffffff',
                            confirmButtonColor: '#FF6F91',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'View Cart',
                            showCancelButton: true,
                            cancelButtonText: 'Continue Shopping',
                            showCloseButton: true,
                            customClass: {
                                popup: 'swal-wide',
                                confirmButton: 'btn btn-primary me-2',
                                cancelButton: 'btn btn-outline-secondary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/cart';
                            }
                        });
                    } else {
                        // Show error with SweetAlert
                        Swal.fire({
                            title: 'Oops!',
                            text: data.message || 'Failed to add item to cart',
                            icon: 'error',
                            color: '#1f2937',
                            background: '#ffffff',
                            confirmButtonColor: '#FF6F91',
                            confirmButtonText: 'Try Again',
                            showCloseButton: true,
                            customClass: {
                                popup: 'swal-wide',
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    // Re-enable button
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            };

            // Toggle wishlist functionality
            window.toggleWishlist = function(productId) {
                // Check if user is authenticated first
                const isUserLoggedIn = document.querySelector('a[href*="login"]') === null &&
                                      document.querySelector('a[href*="logout"], form[action*="logout"]') !== null;

                if (!isUserLoggedIn) {
                    // User is not logged in - show notification with login/register options
                    Swal.fire({
                        title: 'Login Required',
                        html: 'You need to be logged in to save items to your wishlist.<br><br>' +
                              '<strong>Create an account to start saving your favorite products!</strong>',
                        icon: 'info',
                        showCancelButton: true,
                        showCloseButton: true,
                        confirmButtonColor: '#FF6F91',
                        cancelButtonColor: '#28a745',
                        confirmButtonText: '<i class="bi bi-box-arrow-in-right me-2"></i>Login',
                        cancelButtonText: '<i class="bi bi-person-plus me-2"></i>Register',
                        customClass: {
                            popup: 'swal-wide',
                            confirmButton: 'btn btn-primary me-3',
                            cancelButton: 'btn btn-success'
                        },
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Login button clicked
                            window.location.href = '/login';
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Register button clicked
                            window.location.href = '/register';
                        }
                    });
                    return;
                }

                // User is logged in - proceed with wishlist toggle
                const wishlistBtn = document.getElementById('wishlistBtn');
                const wishlistIcon = document.getElementById('wishlistIcon');
                const wishlistText = document.getElementById('wishlistText');

                // Disable button to prevent double clicks
                wishlistBtn.disabled = true;
                const originalIcon = wishlistIcon.className;
                const originalText = wishlistText.textContent;

                // Show loading state
                wishlistIcon.className = 'bi bi-hourglass-split';
                wishlistText.textContent = 'Processing...';

                // Prepare form data
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Make AJAX request
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.status === 419) {
                        // CSRF token expired
                        Swal.fire({
                            title: 'Session Expired',
                            text: 'Please refresh the page and try again',
                            icon: 'warning',
                            confirmButtonColor: '#FF6F91',
                            confirmButtonText: 'Refresh Page'
                        }).then(() => {
                            window.location.reload();
                        });
                        return null;
                    }

                    return response.json();
                })
                .then(data => {
                    if (!data) return;

                    if (data.success) {
                        if (data.action === 'added') {
                            // Product added to wishlist
                            wishlistIcon.className = 'bi bi-heart-fill animate__animated animate__heartBeat';
                            wishlistText.textContent = 'In Wishlist';
                            wishlistBtn.classList.add('in-wishlist');

                            // Show success notification with heart animation
                            Swal.fire({
                                title: 'Added to Wishlist!',
                                text: 'Product has been saved to your wishlist',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                customClass: {
                                    popup: 'animate__animated animate__heartBeat'
                                }
                            });

                        } else if (data.action === 'removed') {
                            // Product removed from wishlist
                            wishlistIcon.className = 'bi bi-heart';
                            wishlistText.textContent = 'Add to Wishlist';
                            wishlistBtn.classList.remove('in-wishlist');

                            // Show info notification
                            Swal.fire({
                                title: 'Removed from Wishlist',
                                text: 'Product has been removed from your wishlist',
                                icon: 'info',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    } else {
                        // Show error
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Failed to update wishlist',
                            icon: 'error',
                            confirmButtonColor: '#FF6F91',
                            confirmButtonText: 'Try Again'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    // Re-enable button
                    wishlistBtn.disabled = false;
                });
            };

            // Check initial wishlist status on page load
            window.checkWishlistStatus = function(productId) {
                const isUserLoggedIn = document.querySelector('a[href*="login"]') === null &&
                                      document.querySelector('a[href*="logout"], form[action*="logout"]') !== null;

                if (!isUserLoggedIn) return; // Skip if not logged in

                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch('/wishlist/check', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.in_wishlist) {
                        const wishlistIcon = document.getElementById('wishlistIcon');
                        const wishlistText = document.getElementById('wishlistText');
                        const wishlistBtn = document.getElementById('wishlistBtn');

                        if (wishlistIcon) wishlistIcon.className = 'bi bi-heart-fill';
                        if (wishlistText) wishlistText.textContent = 'In Wishlist';
                        if (wishlistBtn) wishlistBtn.classList.add('in-wishlist');
                    }
                })
                .catch(error => {
                    console.error('Error checking wishlist status:', error);
                });
            };

            // Check wishlist status on page load
            checkWishlistStatus({{ $product->id }});
        });
    </script>
@endsection
