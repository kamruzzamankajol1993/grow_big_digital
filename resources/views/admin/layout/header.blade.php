<header class="top-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light d-lg-none" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
        <h4 class="mb-0 fw-bold d-none d-md-block text-main">
            @yield('title', 'Dashboard')
        </h4>
    </div>

    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('/clear') }}" class="btn btn-outline-danger btn-sm d-none d-md-flex align-items-center gap-2 px-3 rounded-pill" id="cacheBtn">
            <i class="bi bi-arrow-clockwise"></i> Clear Cache
        </a>

        <div class="dropdown">
            <div class="profile-btn d-flex align-items-center gap-2" data-bs-toggle="dropdown" style="cursor: pointer;">
                <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="avatar" alt="profile">
                <div class="d-none d-sm-block">
                    <p class="mb-0 small fw-bold text-dark">{{ Auth::user()->name }}</p>
                </div>
                <i class="bi bi-chevron-down small text-muted"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3">
                <li><a class="dropdown-item" href="{{ route('profileView') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('profileSetting') }}"><i class="bi bi-gear me-2"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>