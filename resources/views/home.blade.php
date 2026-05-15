@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content')
    @php
        $serviceCards = isset($featuredCategories) ? $featuredCategories : collect();
        $cacheBuster = !empty($systemSettings['images_cache_buster']) ? '?v=' . $systemSettings['images_cache_buster'] : '';
        $heroImageUrl = media_url($systemSettings['hero_image_path'] ?? null, asset('img/home-hero-design.webp'), $cacheBuster);
        $heroProduct = $homeFeaturedProducts->first();
    @endphp

    <section class="home-hero" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.82), rgba(37, 99, 235, 0.28)), url('{{ $heroImageUrl }}'); background-size: cover; background-position: center;">
        <div class="container hero-inner">
            <div class="hero-copy reveal-up">
                <span class="hero-kicker">{{ $systemSettings['hero_kicker'] ?? 'Trusted Electronics Store in Tanzania' }}</span>
                <h1>{{ $systemSettings['hero_title'] ?? 'Affordable Tech, Original Devices & Fast Nationwide Delivery' }}</h1>
                <p>{{ $systemSettings['hero_description'] ?? 'Shop phones, laptops, accessories and premium electronics with secure payment, warranty support, and reliable delivery across Tanzania.' }}</p>

                <div class="hero-actions">
                    <a href="{{ route('shop') }}" class="hero-btn hero-btn--primary">
                        {{ $systemSettings['hero_button_primary'] ?? 'Shop Now' }}
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('categories') }}" class="hero-btn hero-btn--secondary">
                        {{ $systemSettings['hero_button_secondary'] ?? 'Explore Categories' }}
                        <i class="bi bi-grid"></i>
                    </a>
                </div>

                <div class="hero-highlights">
                    <div>
                        <strong>✅ {{ $systemSettings['hero_highlight_1_title'] ?? 'Original Products' }}</strong>
                        <span>{{ $systemSettings['hero_highlight_1_description'] ?? 'Verified electronics' }}</span>
                    </div>
                    <div>
                        <strong>✅ {{ $systemSettings['hero_highlight_2_title'] ?? 'Fast Delivery' }}</strong>
                        <span>{{ $systemSettings['hero_highlight_2_description'] ?? 'Across Tanzania' }}</span>
                    </div>
                    <div>
                        <strong>✅ {{ $systemSettings['hero_highlight_3_title'] ?? 'Warranty Included' }}</strong>
                        <span>{{ $systemSettings['hero_highlight_3_description'] ?? 'Peace of mind on every order' }}</span>
                    </div>
                </div>
            </div>

            <div class="hero-preview reveal-up reveal-delay-1">
                <div class="hero-preview__card">
                    <div class="hero-preview__badge">Original</div>
                    <div class="hero-preview__image">
                        <img src="{{ $heroImageUrl }}" alt="Electronics hero preview">
                    </div>
                    <div class="hero-preview__meta">
                        <p class="hero-preview__tag">Top seller</p>
                        <strong>{{ $heroProduct->name ?? 'Premium Electronics' }}</strong>
                        @if($heroProduct)
                            <div class="hero-preview__price">
                                Tsh {{ number_format($heroProduct->new_price, 0) }}
                                @if($heroProduct->old_price && $heroProduct->old_price > $heroProduct->new_price)
                                    <small>Tsh {{ number_format($heroProduct->old_price, 0) }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-section--soft">
        <div class="container">
            <div class="section-heading reveal-up">
                <span class="soft-kicker">{{ $systemSettings['home_offer_kicker'] ?? 'What we offer' }}</span>
                <h2>{{ $systemSettings['home_offer_title'] ?? 'Everything feels simple from browsing to checkout.' }}</h2>
            </div>

            <div class="home-products-grid reveal-up reveal-delay-1">
                @forelse($homeFeaturedProducts as $product)
                    <article class="featured-card">
                        <a href="{{ route('shop.show', $product->slug) }}" class="featured-image">
                            <img src="{{ media_url($product->thumbnail, asset('img/logo.png')) }}" alt="{{ $product->name }}">
                            <div class="featured-badges">
                                @if($product->discount > 0)
                                    <span class="badge badge-discount">{{ $product->discount }}% OFF</span>
                                @endif
                                @if($product->created_at->diffInDays(now()) <= 7)
                                    <span class="badge badge-new">New</span>
                                @endif
                            </div>
                        </a>
                        <div class="featured-content">
                            <span class="featured-category">{{ $product->category->name ?? 'Electronics' }}</span>
                            <h3><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></h3>
                            <div class="featured-price">
                                <span>Tsh {{ number_format($product->new_price, 0) }}</span>
                                @if($product->old_price && $product->old_price > $product->new_price)
                                    <small>Tsh {{ number_format($product->old_price, 0) }}</small>
                                @endif
                            </div>
                            <div class="featured-meta">
                                <span class="stock-status {{ $product->stock > 10 ? 'stock-in' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                                    @if($product->stock > 10) In Stock @elseif($product->stock > 0) {{ $product->stock }} left @else Out @endif
                                </span>
                                <span class="product-rating"><i class="bi bi-star-fill"></i> {{ number_format($product->rate ?: 0, 1) }}</span>
                            </div>
                            <a href="{{ route('shop.show', $product->slug) }}" class="featured-cta">
                                View product
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <p>No featured products available yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container">
            <div class="section-heading reveal-up">
                <span class="soft-kicker">{{ $systemSettings['home_category_kicker'] ?? 'Shop by category' }}</span>
                <h2>{{ $systemSettings['home_category_title'] ?? 'Browse top electronics categories' }}</h2>
            </div>

            <div class="category-card-grid reveal-up reveal-delay-1">
                @foreach($featuredCategories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" class="category-card">
                        <div class="category-card__media">
                            <img src="{{ media_url($category->image, asset('img/logo.png')) }}" alt="{{ $category->name }}">
                        </div>
                        <div class="category-card__content">
                            <h3>{{ $category->name }}</h3>
                            <p>{{ $category->description ?? 'Explore the best products in this category.' }}</p>
                        </div>
                        <div class="category-card__icon">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="home-section home-section--trust">
        <div class="container">
            @php
                $trustCards = [
                    ['icon' => 'bi bi-truck', 'title' => $systemSettings['trust_card_1_title'] ?? 'Fast Delivery', 'description' => $systemSettings['trust_card_1_description'] ?? 'Nationwide delivery across Tanzania.'],
                    ['icon' => 'bi bi-lock-fill', 'title' => $systemSettings['trust_card_2_title'] ?? 'Secure Payments', 'description' => $systemSettings['trust_card_2_description'] ?? 'Pay with M-Pesa, bank transfer, or card safely.'],
                    ['icon' => 'bi bi-award', 'title' => $systemSettings['trust_card_3_title'] ?? 'Warranty Included', 'description' => $systemSettings['trust_card_3_description'] ?? 'Warranty support for eligible devices.'],
                    ['icon' => 'bi bi-arrow-repeat', 'title' => $systemSettings['trust_card_4_title'] ?? 'Easy Returns', 'description' => $systemSettings['trust_card_4_description'] ?? 'Simple returns and transparent refund policy.'],
                ];
            @endphp

            <div class="trust-grid reveal-up reveal-delay-1">
                @foreach($trustCards as $card)
                    <div class="trust-card">
                        <i class="{{ $card['icon'] }}"></i>
                        <div>
                            <strong>{{ $card['title'] }}</strong>
                            <p>{{ $card['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="review-panel reveal-up reveal-delay-2">
                <div>
                    <p class="review-quote">“{{ $systemSettings['home_review_quote'] ?? 'Fast delivery, original devices, and payment was easy. My phone arrived the next day with a warranty receipt.' }}”</p>
                    <div class="review-meta">
                        <span class="review-author">{{ $systemSettings['home_review_author'] ?? 'Amina, Dar es Salaam' }}</span>
                        <span class="review-stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </span>
                    </div>
                </div>
                <div class="review-support">
                    <span>{{ $systemSettings['home_review_support_label'] ?? 'Need help now?' }}</span>
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $systemSettings['whatsapp'] ?? '255754321987') }}?text=Hello%2C%20I%20need%20help%20with%20an%20order" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                        {{ $systemSettings['home_review_support_button'] ?? 'WhatsApp support' }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-section--final">
        <div class="container">
            <div class="confidence-panel reveal-up">
                <div>
                    <span class="soft-kicker">{{ $systemSettings['home_confidence_kicker'] ?? 'Safe & reliable shopping' }}</span>
                    <h2>{{ $systemSettings['home_confidence_title'] ?? 'Shop with confidence and fast delivery' }}</h2>
                    <p>
                        {{ $systemSettings['home_confidence_description'] ?? 'From delivery tracking to warranty coverage, Bravus Market gives you a polished electronics shopping experience with clear support and proven reliability.' }}
                    </p>
                </div>

                <div class="confidence-list">
                    <div>
                        <i class="bi bi-truck"></i>
                        {{ $systemSettings['home_confidence_item_1'] ?? 'Fast delivery to all regions of Tanzania' }}
                    </div>
                    <div>
                        <i class="bi bi-shield-lock"></i>
                        {{ $systemSettings['home_confidence_item_2'] ?? 'Secure payment experience with M-Pesa & card options' }}
                    </div>
                    <div>
                        <i class="bi bi-award"></i>
                        {{ $systemSettings['home_confidence_item_3'] ?? 'Warranty included on eligible devices' }}
                    </div>
                    <div>
                        <i class="bi bi-arrow-repeat"></i>
                        {{ $systemSettings['home_confidence_item_4'] ?? 'Easy returns and transparent refund policy' }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
