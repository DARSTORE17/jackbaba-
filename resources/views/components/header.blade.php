@php
    $siteName = $systemSettings['site_name'] ?? config('app.name', 'Bravus Market');
    $siteTagline = $systemSettings['site_tagline'] ?? 'Premium electronics, phones, laptops, and accessories';
    $logoUrl = !empty($systemSettings['logo_path']) ? asset('storage/' . $systemSettings['logo_path']) : asset('img/logo.png');
@endphp

<header class="header">
    <div class="header-container">
        <div class="brand-section">
            <div class="logo-container">
                <div class="logo-main">
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }} Logo" class="logo-img">
                </div>
                <div class="logo-text">
                    <h1 class="school-name">{{ $siteName }}</h1>
                    <p class="school-subtitle">{{ $siteTagline }}</p>
                </div>
            </div>
        </div>

        <div class="header-right-section">
            <nav class="navigation-right">
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item">
                        <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/shop" class="nav-link {{ request()->is('shop') ? 'active' : '' }}">
                            <i class="bi bi-bag-heart"></i>
                            <span>Shop</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/categories" class="nav-link {{ request()->is('categories*') || request()->is('category*') ? 'active' : '' }}">
                            <i class="bi bi-grid"></i>
                            <span>Categories</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/about" class="nav-link {{ request()->is('about') ? 'active' : '' }}">
                            <i class="bi bi-emoji-smile"></i>
                            <span>About Us</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/cart" class="nav-link {{ request()->is('cart') ? 'active' : '' }}">
                            <i class="bi bi-cart-fill"></i>
                            <span>Cart</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/customer/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>My Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        @guest
                            <a href="/login" class="nav-link {{ request()->is('login') ? 'active' : '' }}">
                                <i class="bi bi-person-circle"></i>
                                <span>Login / Sign Up</span>
                            </a>
                        @else
                            <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logout-form">
                                @csrf
                                <button type="submit" class="nav-link logout-btn border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        @endguest
                    </li>
                </ul>
            </nav>
        </div>

        <button class="mobile-toggle" id="mobileToggle" aria-label="Open navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-header">
            <div class="mobile-logo">
                <div class="mobile-logo-main">
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }} Logo" class="mobile-logo-img">
                </div>
                <span>{{ $siteName }}</span>
            </div>
            <button class="mobile-close" id="mobileClose" aria-label="Close navigation">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <nav class="mobile-nav-menu">
            <a href="/" class="mobile-nav-link">
                <i class="bi bi-house-door"></i>
                <span>Home</span>
            </a>

            <a href="/shop" class="mobile-nav-link">
                <i class="bi bi-bag-heart"></i>
                <span>Shop</span>
            </a>


            <a href="/categories" class="mobile-nav-link">
                <i class="bi bi-grid"></i>
                <span>Categories</span>
            </a>
            
            <a href="/about" class="mobile-nav-link">
                <i class="bi bi-emoji-smile"></i>
                <span>About Us</span>
            </a>

            <a href="/cart" class="mobile-nav-link">
                <i class="bi bi-cart-fill"></i>
                <span>Cart</span>
            </a>

            <a href="/customer/dashboard" class="mobile-nav-link">
                <i class="bi bi-speedometer2"></i>
                <span>My Dashboard</span>
            </a>

            @guest
                <a href="/login" class="mobile-nav-link">
                    <i class="bi bi-person-circle"></i>
                    <span>Login</span>
                </a>
            @else
                <form action="{{ route('logout') }}" method="POST" class="d-inline" id="mobile-logout-form">
                    @csrf
                    <button type="submit" class="mobile-nav-link logout-btn border-0 bg-transparent w-100 p-3 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            @endguest
        </nav>
    </div>
</header>
