<aside id="sidebar">
    <div class="p-3">
        <!-- Logo Section -->
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo.png') }}" alt="Bravus Market Logo" class="img-fluid mb-2" style="height: 50px; border-radius: 50%;">
            <h6 class="text-primary fw-bold mb-3">Bravus Market Panel</h6>
        </div>
        <ul class="nav flex-column">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('customer.dashboard') }}"
                    class="nav-link {{ request()->is('customer/dashboard') ? 'active bg-primary text-white fw-bold shadow-sm' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <!-- Browse Products -->
            <li class="nav-item">
                <a href="{{ route('shop') }}"
                    class="nav-link">
                    <i class="bi bi-shop"></i> Products
                </a>
            </li>

            <!-- My Orders -->
            <li class="nav-item">
                <a href="{{ route('customer.orders') }}"
                    class="nav-link {{ request()->is('customer/orders*') ? 'active bg-primary text-white fw-bold shadow-sm' : '' }}">
                    <i class="bi bi-receipt"></i> Orders
                </a>
            </li>

            <!-- Shopping Cart -->
            <li class="nav-item">
                <a href="{{ route('cart.index') }}"
                    class="nav-link {{ request()->is('cart') ? 'active bg-primary text-white fw-bold shadow-sm' : '' }}">
                    <i class="bi bi-cart-fill"></i> Cart
                </a>
            </li>

            <!-- Wishlist -->
            <li class="nav-item">
                <a href="{{ route('customer.wishlist') }}"
                    class="nav-link {{ request()->is('customer/wishlist') ? 'active bg-primary text-white fw-bold shadow-sm' : '' }}">
                    <i class="bi bi-heart-fill"></i> Wishlist
                </a>
            </li>

            <!-- Addresses -->
            <li class="nav-item">
                <a href="{{ route('customer.addresses') }}"
                    class="nav-link {{ request()->is('customer/addresses') ? 'active bg-primary text-white fw-bold shadow-sm' : '' }}">
                    <i class="bi bi-geo-alt-fill"></i> Addresses
                </a>
            </li>
        </ul>
    </div>
</aside>
