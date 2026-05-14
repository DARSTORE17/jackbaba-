@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content')
    @php
        $serviceCards = isset($featuredCategories) ? $featuredCategories : collect();
        $cacheBuster = !empty($systemSettings['images_cache_buster']) ? '?v=' . $systemSettings['images_cache_buster'] : '';
        $heroVideoUrl = media_url($systemSettings['hero_video_path'] ?? null, asset('videos/hero-electronics.mp4'), $cacheBuster);
        $heroImageUrl = media_url($systemSettings['hero_image_path'] ?? null, asset('img/hero-toys.png'), $cacheBuster);
    @endphp

    <section class="home-hero">
        <video class="home-hero__video" autoplay muted loop playsinline preload="none" poster="{{ $heroImageUrl }}">
            <source data-src="{{ $heroVideoUrl }}" type="video/mp4">
        </video>

        <script>
            window.addEventListener('load', function () {
                const heroVideo = document.querySelector('.home-hero__video');
                const source = heroVideo ? heroVideo.querySelector('source[data-src]') : null;
                if (source && source.dataset.src) {
                    source.src = source.dataset.src;
                    heroVideo.load();
                }
            });
        </script>

        <div class="home-hero__veil"></div>
        <div class="home-hero__grid"></div>

        <div class="home-hero__spark spark-one"></div>
        <div class="home-hero__spark spark-two"></div>
        <div class="home-hero__spark spark-three"></div>

        <div class="container home-hero__inner">
            <div class="home-hero__copy reveal-up">
                <span class="soft-kicker">
                    <i class="bi bi-lightning-charge-fill"></i>
                    {{ $systemSettings['hero_kicker'] ?? 'Premium electronics in Tanzania' }}
                </span>

                <h1>{{ $systemSettings['hero_title'] ?? 'Bravus Market' }}</h1>

                <p class="home-hero__lead">
                    {{ $systemSettings['hero_description'] ?? 'Shop original phones, laptops, and smart accessories with a clean, trusted, and fast buying experience.' }}
                </p>

                <div class="home-hero__actions">
                    <a href="{{ route('shop') }}" class="home-btn home-btn--primary">
                        <span>Shop Now</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('categories') }}" class="home-btn home-btn--ghost">
                        <span>Explore Categories</span>
                        <i class="bi bi-grid"></i>
                    </a>
                </div>

            </div>

            <div class="home-showcase reveal-up reveal-delay-2" aria-label="Featured electronics">
                <div class="showcase-card showcase-card--main">
                    <div class="showcase-card__top">
                        <span>{{ $systemSettings['site_tagline'] ?? '' }}</span>
                        <i class="bi bi-stars"></i>
                    </div>
                    <img src="{{ $heroImageUrl }}" alt="Featured electronics at {{ $systemSettings['site_name'] ?? 'Bravus Market' }}">
                    <div class="showcase-card__bottom">
                        <div>
                            <strong>{{ $systemSettings['site_name'] ?? '' }}</strong>
                            <small>{{ $systemSettings['site_description'] ?? '' }}</small>
                        </div>
                        <a href="{{ route('shop') }}" aria-label="View smart tech picks">
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>
                </div>

                <div class="floating-chip chip-phone">
                    <i class="bi bi-phone-fill"></i>
                    {{ $serviceCards->get(0)->name ?? ($systemSettings['site_name'] ?? '') }}
                </div>
                <div class="floating-chip chip-laptop">
                    <i class="bi bi-laptop-fill"></i>
                    {{ $serviceCards->get(1)->name ?? ($systemSettings['site_tagline'] ?? '') }}
                </div>
                <div class="floating-chip chip-audio">
                    <i class="bi bi-earbuds"></i>
                    {{ $serviceCards->get(2)->name ?? ($systemSettings['phone'] ?? '') }}
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-section--soft">
        <div class="container">
            <div class="section-heading reveal-up">
                <span class="soft-kicker">{{ $systemSettings['home_offer_kicker'] ?? '' }}</span>
                <h2>{{ $systemSettings['home_offer_title'] ?? '' }}</h2>
            </div>

            <div class="offer-grid">
                @foreach($serviceCards as $index => $card)
                    @php
                        $hasCategoryImage = isset($featuredCategories)
                            && $featuredCategories->count()
                            && media_exists($card->image);
                        $imageUrl = $hasCategoryImage ? media_url($card->image) : null;
                    @endphp

                    <a href="{{ isset($card->slug) ? route('category.show', $card->slug) : route('shop') }}"
                        class="offer-card reveal-up reveal-delay-{{ min($index + 1, 4) }}">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $card->name }}">
                        @else
                            <div class="offer-card__icon">
                                <i class="bi {{ $card->icon ?? 'bi-stars' }}"></i>
                            </div>
                        @endif

                        <div class="offer-card__content">
                            <h3>{{ $card->name }}</h3>
                            <p>{{ $card->description }}</p>
                            <span>
                                View deals
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="home-section home-section--final">
        <div class="container">
            <div class="confidence-panel reveal-up">
                <div>
                    <span class="soft-kicker">{{ $systemSettings['home_confidence_kicker'] ?? '' }}</span>
                    <h2>{{ $systemSettings['home_confidence_title'] ?? '' }}</h2>
                    <p>
                        {{ $systemSettings['home_confidence_description'] ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
