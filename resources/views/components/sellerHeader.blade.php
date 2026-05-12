<header id="main-header" class="d-flex align-items-center justify-content-between bg-white border-bottom shadow-sm px-3">
    <div class="d-flex align-items-center">
        <!-- Sidebar toggle button for mobile -->
        <button id="sidebarToggle" class="btn btn-outline-secondary d-md-none me-3" type="button" aria-label="Toggle sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

                <!-- Title -->
        <span class="subheading ms-2 fs-5 fw-bold text-primary">
            Bravus Market - Seller Dashboard
        </span>
    </div>

    <!-- Pro    file section -->
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            @if(Auth::check() && Auth::user() && Auth::user()->passport)
                <img src="{{ asset('storage/' . Auth::user()->passport) }}"
                     alt="Profile Picture" class="profile-avatar" />
            @else
                @php $displayName = Auth::check() && Auth::user() ? Auth::user()->name : 'User'; @endphp
                <img src="https://ui-avatars.com/api/?name={{ urlencode($displayName) }}&background=667eea&color=fff&size=40"
                     alt="Profile Picture" class="profile-avatar" />
            @endif
            <span class="ms-2 d-none d-md-inline">{{ Auth::check() && Auth::user() ? Auth::user()->name : '' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('seller.settings') }}">
                    <i class="bi bi-person-gear text-primary me-2"></i> Profile & Settings
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center">
                        <i class="bi bi-box-arrow-right text-danger me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>
