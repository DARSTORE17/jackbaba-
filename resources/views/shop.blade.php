@extends('layouts.app')

@section('title', 'Shop - Bravus Market')

@section('css')
<style>
    :root {
        --primary-color: #2563EB;
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

    /* Mobile-first responsive design */
    .shop-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
    }

    /* Search enhanced styling */
    .search-focused .search-bar input {
        transform: scale(1.02);
        box-shadow: 0 0 0 3px rgba(255, 111, 145, 0.2);
    }

    /* Search Bar */
    .search-container {
        margin-bottom: 30px;
    }

    .search-bar {
        position: relative;
        max-width: 500px;
        margin: 0 auto;
    }

    .search-bar input {
        width: 100%;
        padding: 12px 45px 12px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
    }

    .search-bar input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 111, 145, 0.1);
    }

    .search-bar button {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-bar button:hover {
        background: #e63869;
        transform: translateY(-50%) scale(1.1);
    }



    /* Masonry Layout */
    .products-grid {
        margin-bottom: 40px;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        position: relative;
        break-inside: avoid;
        margin-bottom: 20px;
    }

    .product-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-5px);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        background: var(--light-bg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        max-width: 100%;
        max-height: 300px; /* optional max height */
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .product-badge {
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-discount {
        background: var(--danger-color);
        color: white;
    }

    .badge-new {
        background: var(--success-color);
        color: white;
    }

    .badge-advertised {
        background: linear-gradient(135deg, #2563EB, #2563EB);
        color: white;
    }

    .product-info {
        padding: 15px;
    }

    .product-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-title:hover {
        color: var(--primary-color);
    }

    .product-prices {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .product-old-price {
        font-size: 14px;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 10px;
    }

    .stars {
        display: flex;
        gap: 2px;
    }

    .star {
        font-size: 12px;
        color: #fbbf24;
    }

    .rating-count {
        font-size: 12px;
        color: #6b7280;
    }

    .product-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6b7280;
    }

    .stock-status {
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 500;
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

    /* Pagination */
    .pagination-custom {
        display: flex;
        justify-content: center;
        margin: 40px 0;
    }

    .pagination-custom .page-link {
        border: none;
        background: transparent;
        color: var(--text-dark);
        padding: 10px 15px;
        margin: 0 2px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .pagination-custom .page-link:hover,
    .pagination-custom .page-link.active {
        background: var(--primary-color);
        color: white;
    }

    /* Loading States */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .product-skeleton {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .product-skeleton .product-image {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    .product-skeleton .product-info {
        padding: 15px;
    }

    .product-skeleton .skeleton-text {
        background: #f0f0f0;
        height: 12px;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .product-skeleton .skeleton-text:last-child {
        width: 60%;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .controls-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        margin-bottom: 30px;
        justify-content: space-between;
    }

    /* Categories Section */
    .categories-section {
        margin-bottom: 30px;
        text-align: center;
    }

    .categories-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 15px;
    }

    .categories-grid {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 10px;
        overflow-x: auto;
        padding: 10px 0;
        width: 100%;
        scrollbar-width: thin;
    }

    .categories-grid::-webkit-scrollbar {
        height: 6px;
    }

    .categories-grid::-webkit-scrollbar-track {
        background: transparent;
    }

    .categories-grid::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 2px;
    }

    .categories-grid::-webkit-scrollbar-thumb:hover {
        background-color: #e63869;
    }

    .category-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        white-space: nowrap;
    }

    .category-pill:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .category-pill-selected {
        background: var(--primary-color);
        color: white;
    }

    .category-pill-selected::after {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background: var(--success-color);
        border-radius: 50%;
        border: 2px solid white;
    }

    .category-pill-default {
        background: white;
        color: var(--text-dark);
        border-color: #e5e7eb;
    }

    .category-pill-default:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    @media (min-width: 1200px) {
        .controls-group {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            flex-wrap: nowrap;
        }

        .controls-row-1 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .controls-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            gap: 15px;
            width: 100%;
            padding-left: 10px;
        }
    }

    .search-small {
        display: flex;
        align-items: center;
    }

    .search-small input {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        flex: 1;
        min-width: 400px;
    }

    .search-small button {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        background: var(--primary-color);
        color: white;
        border-radius: 8px;
        cursor: pointer;
        margin-left: 8px;
    }

    .sort-dropdown,
    .category-filter,
    .price-filter {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .sort-dropdown select,
    .category-filter select,
    .price-filter input {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        min-width: 400px;
    }

    .price-range {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .shop-container {
            padding: 10px;
        }

        .products-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-card {
            display: flex;
            padding: 10px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 0;
        }

        .product-image {
            flex: 0 0 120px;
            height: auto;
        }

        .product-info {
            flex: 1;
            padding-left: 15px;
            padding-top: 0;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .controls-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .results-count {
            text-align: center;
        }

        .controls-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .sort-dropdown, .category-filter, .price-filter {
            flex: 1 1 auto;
            min-width: 0;
        }

        .search-small {
            flex: 1 1 200px;
        }
    }

    @media (min-width: 769px) {
        .products-grid {
            column-count: 5;
            column-gap: 15px;
        }

        .shop-container {
            max-width: 1400px;
        }
    }

    @media (min-width: 1200px) {
        .products-grid {
            column-count: 6;
        }
    }

    /* Filter Toggle Animation */
    .filter-toggle-icon {
        transition: transform 0.3s ease;
    }

    .filters-header.collapsed .filter-toggle-icon {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('content')
<main class="shop-container">
<!-- Search and Categories in Header -->
<div class="shop-header-sticky" style="position: sticky; top: 0; z-index: 100; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #f0f0f0; padding: 20px 0;">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Search Bar Section -->
                <div class="search-section mb-3">
                    <form method="GET" action="{{ route('shop') }}">
                        <div class="d-flex justify-content-center">
                            <div class="search-bar position-relative" style="max-width: 500px; width: 100%;">
                                <input type="text" class="form-control" style="border-radius: 50px; padding: 12px 45px 12px 20px; font-size: 16px;" name="search" placeholder="Search products..." value="{{ request('search') }}">
                                <button type="submit" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: var(--primary-color); color: white; border: none; border-radius: 50%; width: 36px; height: 36px;" class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Categories Section -->
                <div class="categories-header-section">
                    <div class="categories-grid">
                        @php
                            $allCategoriesQuery = request()->query();
                            unset($allCategoriesQuery['category']);
                        @endphp
                        <a href="{{ route('shop') }}?{{ http_build_query($allCategoriesQuery) }}" class="category-pill {{ !request('category') ? 'category-pill-selected' : 'category-pill-default' }}">
                            <i class="bi bi-grid-fill me-1"></i>All Categories
                        </a>
                        @foreach($categories as $category)
                            @php
                                $categoryQuery = request()->query();
                                unset($categoryQuery['search']);
                                $categoryQuery['category'] = $category->id;
                            @endphp
                        <a href="{{ route('shop') }}?{{ http_build_query($categoryQuery) }}" class="category-pill {{ request('category') == $category->id ? 'category-pill-selected' : 'category-pill-default' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Controls Bar -->
<div class="controls-bar" style="display: none;">
</div>

    <!-- Products Grid -->
    <div class="products-grid" id="productsContainer">
        @forelse($products as $product)
        <article class="product-card">
            <div class="product-image">
                <a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none">
                    <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('img/logo.png') }}" alt="{{ $product->name }}" loading="lazy">
                </a>
                <div class="product-badges">
                    @if($product->discount > 0)
                        <span class="product-badge badge-discount">{{ $product->discount }}% OFF</span>
                    @endif
                    @if($product->is_advertised)
                        <span class="product-badge badge-advertised">Featured</span>
                    @endif
                    @if($product->created_at->diffInDays(now()) <= 7)
                        <span class="product-badge badge-new">New</span>
                    @endif
                </div>
            </div>

            <div class="product-info">
                <h3 class="product-title">
                    <a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none">
                        {{ $product->name }}
                    </a>
                </h3>

                <div class="product-prices">
        <span class="product-price">Tsh{{ number_format($product->new_price, 2) }}</span>
                    @if($product->old_price && $product->old_price > $product->new_price)
                        <span class="product-old-price">Tsh{{ number_format($product->old_price, 2) }}</span>
                    @endif
                </div>

                @if($product->rate > 0)
                <div class="product-rating">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $product->rate ? 'bi-star-fill' : 'bi-star' }} star"></i>
                        @endfor
                    </div>
                    <span class="rating-count">({{ $product->rate }}.0)</span>
                </div>
                @endif

                <div class="product-meta">
                    <span class="stock-status {{ $product->stock > 10 ? 'stock-in' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                        @if($product->stock > 10)
                            In Stock
                        @elseif($product->stock > 0)
                            Only {{ $product->stock }} left
                        @else
                            Out of Stock
                        @endif
                    </span>
                    <span class="category">
                        <i class="bi bi-tag-fill"></i> {{ $product->category->name ?? 'Uncategorized' }}
                    </span>
                </div>
            </div>
        </article>
        @empty
        <div class="no-products-found d-flex align-items-center justify-content-center" style="min-height: 60vh;">
            <div class="text-center">
             
                <i class="bi bi-search text-muted animated-icon" style="font-size: 5rem; animation: pulse 2s infinite; display: block;"></i><br>
                <p class="text-primary">
                    {{ __('Try adjusting your search criteria or browse different categories') }}
                </p><br>
                <a href="{{ route('shop') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left me-2"></i>Back to Shop
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    {{ $products->appends(request()->query())->links() }}

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-search functionality
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = searchInput.closest('form');
    let searchTimeout;

    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchValue = e.target.value.trim();
            if (searchValue.length >= 2 || searchValue.length === 0) {
                searchForm.submit();
            }
        }, 800); // Wait 800ms after user stops typing
    });

    // Enter key behavior
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission
            searchForm.submit();
        }
    });

    // Clear search button
    const searchButton = searchForm.querySelector('button[type="submit"]');
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        searchForm.submit();
    });

    // Enhance search input styling
    searchInput.addEventListener('focus', function() {
        this.parentElement.classList.add('search-focused');
    });

    searchInput.addEventListener('blur', function() {
        this.parentElement.classList.remove('search-focused');
    });

    // Change sorting
    function changeSort(value) {
        const [sortBy, sortOrder] = value.split('-');
        const url = new URL(window.location);
        url.searchParams.set('sort_by', sortBy);
        url.searchParams.set('sort_order', sortOrder);
        window.location.href = url.toString();
    }

    // Expose sorting function globally
    window.changeSort = changeSort;

    // Lazy loading images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));
    }
});
</script>
@endsection
