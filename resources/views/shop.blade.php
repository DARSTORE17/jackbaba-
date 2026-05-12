@extends('layouts.app')

@section('title', 'Shop - ' . ($systemSettings['site_name'] ?? 'Bravus Market'))

@section('css')
    <link href="{{ asset('css/shop.css') }}" rel="stylesheet">
@endsection

@section('content')
    @php
        $activeCategory = $categories->firstWhere('id', (int) request('category'));
        $totalProducts = $products->total();
        $sortValue = request('sort_by', 'created_at') . '-' . request('sort_order', 'desc');
        $saleQuery = request()->query();
        $saleQuery['on_sale'] = request('on_sale') ? null : 1;
        $saleQuery = array_filter($saleQuery, fn ($value) => !is_null($value) && $value !== '');
    @endphp

    <main class="shop-page">
        <section class="shop-hero">
            <div class="shop-hero__glow shop-hero__glow--one"></div>
            <div class="shop-hero__glow shop-hero__glow--two"></div>

            <div class="container">
                <div class="shop-hero__inner reveal-up">
                    <div>
                        <span class="shop-kicker">
                            <i class="bi bi-bag-heart-fill"></i>
                            {{ $systemSettings['shop_kicker'] ?? 'Bravus Market shop' }}
                        </span>
                        <h1>{{ $activeCategory ? $activeCategory->name : ($systemSettings['shop_title'] ?? 'Shop electronics with confidence.') }}</h1>
                        <p>
                            {{ $systemSettings['shop_description'] ?? 'Browse original phones, laptops, accessories, and smart tech with a soft, simple shopping experience.' }}
                        </p>
                    </div>

                    <div class="shop-hero__stats">
                        <div>
                            <strong>{{ number_format($totalProducts) }}</strong>
                            <span>Products available</span>
                        </div>
                        <div>
                            <strong>{{ $categories->count() }}</strong>
                            <span>Categories</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="shop-toolbar-wrap">
            <div class="container">
                <div class="shop-toolbar reveal-up reveal-delay-1">
                    <form method="GET" action="{{ route('shop') }}" class="shop-search" id="shopSearchForm">
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            name="search"
                            placeholder="Search phones, laptops, chargers..."
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                        <button type="submit" aria-label="Search products">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                    <div class="shop-actions">
                        <a href="{{ route('shop') }}?{{ http_build_query($saleQuery) }}"
                            class="filter-chip {{ request('on_sale') ? 'is-active' : '' }}">
                            <i class="bi bi-lightning-charge-fill"></i>
                            Deals
                        </a>

                        <label class="sort-select">
                            <i class="bi bi-sliders2"></i>
                            <select id="shopSortSelect" aria-label="Sort products">
                                <option value="created_at-desc" {{ $sortValue === 'created_at-desc' ? 'selected' : '' }}>Newest</option>
                                <option value="new_price-asc" {{ $sortValue === 'new_price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="new_price-desc" {{ $sortValue === 'new_price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="rate-desc" {{ $sortValue === 'rate-desc' ? 'selected' : '' }}>Top Rated</option>
                                <option value="name-asc" {{ $sortValue === 'name-asc' ? 'selected' : '' }}>Name A-Z</option>
                            </select>
                        </label>
                    </div>
                </div>

                <nav class="shop-categories reveal-up reveal-delay-2" aria-label="Shop categories">
                    @php
                        $allCategoriesQuery = request()->query();
                        unset($allCategoriesQuery['category'], $allCategoriesQuery['page']);
                    @endphp
                    <a href="{{ route('shop') }}?{{ http_build_query($allCategoriesQuery) }}"
                        class="category-pill {{ !request('category') ? 'is-active' : '' }}">
                        <i class="bi bi-grid-fill"></i>
                        All
                    </a>

                    @foreach($categories as $category)
                        @php
                            $categoryQuery = request()->query();
                            unset($categoryQuery['page']);
                            $categoryQuery['category'] = $category->id;
                        @endphp
                        <a href="{{ route('shop') }}?{{ http_build_query($categoryQuery) }}"
                            class="category-pill {{ request('category') == $category->id ? 'is-active' : '' }}">
                            <span>{{ $category->name }}</span>
                            <small>{{ $category->products_count }}</small>
                        </a>
                    @endforeach
                </nav>
            </div>
        </section>

        <section class="shop-products-section">
            <div class="container">
                <div class="shop-results-line reveal-up reveal-delay-3">
                    <span>
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                        of {{ $totalProducts }} products
                    </span>

                    @if(request('search') || request('category') || request('on_sale'))
                        <a href="{{ route('shop') }}">
                            <i class="bi bi-x-circle"></i>
                            Clear filters
                        </a>
                    @endif
                </div>

                <div class="products-grid" id="productsContainer">
                    @forelse($products as $product)
                        <article class="product-card reveal-up">
                            <a href="{{ route('shop.show', $product->slug) }}" class="product-image">
                                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('img/logo.png') }}"
                                    alt="{{ $product->name }}"
                                    loading="lazy">

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
                            </a>

                            <div class="product-info">
                                <div class="product-category">
                                    <i class="bi bi-tag-fill"></i>
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </div>

                                <h2 class="product-title">
                                    <a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
                                </h2>

                                <div class="product-prices">
                                    <span class="product-price">Tsh {{ number_format($product->new_price, 0) }}</span>
                                    @if($product->old_price && $product->old_price > $product->new_price)
                                        <span class="product-old-price">Tsh {{ number_format($product->old_price, 0) }}</span>
                                    @endif
                                </div>

                                <div class="product-footer">
                                    @if($product->rate > 0)
                                        <div class="product-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <span>{{ number_format($product->rate, 1) }}</span>
                                        </div>
                                    @else
                                        <div class="product-rating product-rating--muted">
                                            <i class="bi bi-star"></i>
                                            <span>New</span>
                                        </div>
                                    @endif

                                    <span class="stock-status {{ $product->stock > 10 ? 'stock-in' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                                        @if($product->stock > 10)
                                            In Stock
                                        @elseif($product->stock > 0)
                                            {{ $product->stock }} left
                                        @else
                                            Out
                                        @endif
                                    </span>
                                </div>

                                <a href="{{ route('shop.show', $product->slug) }}" class="product-view-link">
                                    View product
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="empty-shop reveal-up">
                            <i class="bi bi-search"></i>
                            <h2>No products found</h2>
                            <p>Try another search term or browse all categories.</p>
                            <a href="{{ route('shop') }}" class="empty-shop__btn">
                                <i class="bi bi-arrow-left"></i>
                                Back to Shop
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="shop-pagination">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="search"]');
            const searchForm = document.getElementById('shopSearchForm');
            const sortSelect = document.getElementById('shopSortSelect');
            let searchTimeout;

            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function (event) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function () {
                        const value = event.target.value.trim();

                        if (value.length >= 2 || value.length === 0) {
                            searchForm.submit();
                        }
                    }, 700);
                });
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', function () {
                    const [sortBy, sortOrder] = this.value.split('-');
                    const url = new URL(window.location.href);

                    url.searchParams.set('sort_by', sortBy);
                    url.searchParams.set('sort_order', sortOrder);
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }
        });
    </script>
@endsection
