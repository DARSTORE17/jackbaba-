@php
    $siteName = $systemSettings['site_name'] ?? config('app.name', 'Bravus Market');
    $siteTagline = $systemSettings['site_tagline'] ?? '';
    $siteDescription = $systemSettings['site_description'] ?? '';
    $cacheBuster = !empty($systemSettings['images_cache_buster']) ? '?v=' . $systemSettings['images_cache_buster'] : '';
    $logoUrl = media_url($systemSettings['logo_path'] ?? null, asset('img/logo.png'), $cacheBuster);
    $socialLinks = [
        'facebook_url' => 'bi-facebook',
        'instagram_url' => 'bi-instagram',
        'tiktok_url' => 'bi-tiktok',
        'youtube_url' => 'bi-youtube',
    ];
@endphp

<footer class="footer">
    <div class="footer-container">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }} Logo" class="footer-logo-img">
                    <div class="footer-logo-text">
                        <h3 class="footer-school-name">{{ $siteName }}</h3>
                        <p class="footer-school-subtitle">{{ $siteTagline }}</p>
                    </div>
                </div>

                <p class="footer-description">{{ $siteDescription }}</p>

                <div class="footer-social-links">
                    @foreach($socialLinks as $key => $icon)
                        @if(!empty($systemSettings[$key]))
                            <a href="{{ $systemSettings[$key] }}" class="footer-social-link" target="_blank" rel="noopener noreferrer">
                                <i class="bi {{ $icon }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="footer-links-grid">
                <div class="footer-column footer-quick-links">
                    <h4 class="footer-column-title">Quick Links</h4>
                    <ul class="footer-menu">
                        <li><a href="/" class="footer-link">Home</a></li>
                        <li><a href="{{ route('shop') }}" class="footer-link">Shop</a></li>
                        <li><a href="{{ route('categories') }}" class="footer-link">Categories</a></li>
                        <li><a href="/about" class="footer-link">About Us</a></li>
                        <li><a href="/cart" class="footer-link">Cart</a></li>
                    </ul>
                </div>

                <div class="footer-column footer-programs">
                    <h4 class="footer-column-title">Top Categories</h4>
                    <ul class="footer-menu">
                        @forelse($footerCategories ?? [] as $category)
                            <li>
                                <a href="{{ route('category.show', $category->slug) }}" class="footer-link">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @empty
                            <li><a href="{{ route('categories') }}" class="footer-link">Browse Categories</a></li>
                        @endforelse
                    </ul>
                </div>

                <div class="footer-column footer-contact">
                    <h4 class="footer-column-title">Contact Us</h4>
                    <div class="footer-contact-info">
                        <div class="footer-contact-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $systemSettings['address'] ?? '' }}</span>
                        </div>

                        <div class="footer-contact-item">
                            <i class="bi bi-telephone"></i>
                            <span>{{ $systemSettings['phone'] ?? '' }}</span>
                        </div>

                        <div class="footer-contact-item">
                            <i class="bi bi-envelope"></i>
                            <span>{{ $systemSettings['email'] ?? '' }}</span>
                        </div>

                        <div class="footer-contact-item">
                            <i class="bi bi-clock"></i>
                            <span>{{ $systemSettings['business_hours'] ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
                </p>

                <div class="footer-bottom-links">
                    <a href="{{ route('shop') }}" class="footer-bottom-link">Shop</a>
                    <a href="{{ route('categories') }}" class="footer-bottom-link">Categories</a>
                    <a href="/about" class="footer-bottom-link">About</a>
                </div>
            </div>
        </div>
    </div>
</footer>
