<!-- Header Component for Kids Shop - Modern Pink/Aqua Theme -->
<header class="header">
    <!-- Particle Network Background -->
    <div class="particles-container" id="particlesContainer"></div>
    
    <div class="header-container">
        <!-- Left Section - Logo and Shop Info -->
        <div class="brand-section">
            <div class="logo-container">
                <div class="logo-main">
                    <img src="{{ asset('img/logo.png') }}" alt="Kids Shop Logo" class="logo-img">
                </div>
                <div class="logo-text">
                    <h1 class="school-name">KidsStore365</h1>
                    <p class="school-subtitle">“Cute • Fun • Safe for Every Child”</p>
                </div>
            </div>
        </div>

        <!-- Right Section - Navigation and Actions -->
        <div class="header-right-section">
            <!-- Navigation -->
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
                                <span>Login</span>
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

        <!-- Mobile Menu Button -->
        <button class="mobile-toggle" id="mobileToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <!-- Particle Network Background for Mobile Sidebar -->
        <div class="mobile-particles-container" id="mobileParticlesContainer"></div>
        
        <div class="mobile-nav-header">
            <div class="mobile-logo">
                <div class="mobile-logo-main">
                    <img src="{{ asset('img/logo.png') }}" alt="Kids Shop Logo" class="mobile-logo-img">
                </div>
                <span>KidsStore365</span>
            </div>
            <button class="mobile-close" id="mobileClose">
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
