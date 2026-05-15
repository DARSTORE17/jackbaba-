<aside id="sidebar">
    <div class="p-3">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo.png') }}" alt="Bravus Market Logo" class="img-fluid mb-2" style="height: 50px; border-radius: 50%;">
            <h6 class="text-primary fw-bold mb-3">Admin Control Panel</h6>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.sellers') }}" class="nav-link {{ request()->is('admin/sellers*') ? 'active' : '' }}">
                    <i class="bi bi-person-lines-fill"></i> Sellers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Customers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.products') }}" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }}">
                    <i class="bi bi-sliders"></i> Settings
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.database') }}" class="nav-link {{ request()->is('admin/database*') ? 'active' : '' }}">
                    <i class="bi bi-database-gear"></i> Database
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.security.index') }}" class="nav-link {{ request()->is('admin/security*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i> Security
                </a>
            </li>
        </ul>
    </div>
</aside>
