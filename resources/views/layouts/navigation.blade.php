<aside class="sidebar" id="app-sidebar">
    <!-- Header -->
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <i class="bi bi-book-half"></i>
            E-SISWI
        </a>
        <button class="sidebar-close" id="sidebar-close-btn" aria-label="Close sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            Dashboard
        </a>

        @if(Auth::user()->isStudent() || Auth::user()->isAdmin())
            <a href="{{ route('complaints.index') }}"
                class="sidebar-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-dots-fill"></i>
                Complaints
            </a>
            <a href="{{ route('compounds.index') }}"
                class="sidebar-link {{ request()->routeIs('compounds.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Compounds
            </a>
            <a href="{{ route('payments.index') }}"
                class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-fill"></i>
                Payments
            </a>
        @endif

        @if(Auth::user()->isAdmin())
            <div class="sidebar-label">Management</div>
            <a href="{{ route('admin.students') }}"
                class="sidebar-link {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                Students
            </a>
            @if(Auth::user()->isHepaStaff())
                <a href="{{ route('admin.wardens') }}"
                    class="sidebar-link {{ request()->routeIs('admin.wardens') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    Wardens
                </a>
            @endif
        @endif

        <div class="sidebar-label">Account</div>
        <a href="{{ route('profile.edit') }}"
            class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i>
            Profile
        </a>
        <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
            @csrf
            <a href="#" class="sidebar-link text-danger"
                onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </form>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="help-card">
            <small class="d-block mb-1">Need help?</small>
            <a href="#">Contact Support</a>
        </div>
    </div>
</aside>