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
                <a href="{{ route('seller.dashboard') }}"
                    class="nav-link {{ request()->is('seller/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <!-- My Store -->
            <li class="nav-item">
                <a href="{{ route('seller.my-store') }}"
                    class="nav-link {{ request()->is('seller/my-store') ? 'active bg-primary text-white fw-bold shadow-sm' : '' }}">
                    <i class="bi bi-shop"></i> My Store
                </a>
            </li>
            <!-- Products -->
            <li class="nav-item">
                <a href="{{ route('seller.products') }}"
                    class="nav-link {{ request()->is('seller/products*') ? 'active bg-primary text-white fw-bold shadow-sm' : '' }}">
                    <i class="bi bi-basket-fill"></i> Products
                </a>
            </li>
            <!-- Categories -->
            <li class="nav-item">
                <a href="{{ route('seller.categories') }}"
                    class="nav-link {{ request()->is('seller/categories') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
            </li>
            <!-- Orders -->
            <li class="nav-item">
                <a href="{{ route('seller.orders') }}"
                    class="nav-link {{ request()->is('seller/orders*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> Orders
                </a>
            </li>
            <!-- Customers -->
            <li class="nav-item">
                <a href="{{ route('seller.customers') }}"
                    class="nav-link {{ request()->is('seller/customers') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Customers
                </a>
            </li>
            <!-- Analytics -->
            <li class="nav-item">
                <a href="{{ route('seller.analytics') }}"
                    class="nav-link {{ request()->is('seller/analytics') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i> Analytics
                </a>
            </li>
            <!-- Settings -->
            <li class="nav-item">
                <a href="{{ route('seller.settings') }}"
                    class="nav-link {{ request()->is('seller/settings') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> Settings
                </a>
            </li>
        </ul>
    </div>
</aside>
