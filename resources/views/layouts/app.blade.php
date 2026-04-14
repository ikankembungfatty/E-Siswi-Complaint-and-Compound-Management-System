<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-SISWI') }} &mdash; UPTM</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Early theme loader (prevents flash) -->
    <script>
        (function () {
            const t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>

    <!-- Custom Design System -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Sidebar backdrop (mobile only) -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="app-wrapper">
        <!-- ── Sidebar ── -->
        @include('layouts.navigation')

        <!-- ── Main Content ── -->
        <div class="main-content">

            <!-- ── Topbar ── -->
            <header class="topbar">
                <!-- Left: hamburger (mobile) + page title -->
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggler" id="sidebarToggler" aria-label="Open menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <h5 class="topbar-title" id="page-title">Student Portal</h5>
                </div>

                <!-- Right: actions -->
                <div class="topbar-actions">
                    <!-- Theme toggle -->
                    <button class="icon-btn" id="theme-toggle" aria-label="Toggle theme">
                        <i class="bi bi-moon-fill" id="theme-icon"></i>
                    </button>

                    <!-- User dropdown -->
                    <div class="topbar-dropdown">
                        <button class="user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ede9fe&color=6a5ae0&size=68"
                                alt="{{ Auth::user()->name }}">
                            <span class="user-name d-none d-sm-block">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- ── Page Slot ── -->
            <main class="page-content">
                {{ $slot }}
            </main>

            <!-- ── Footer ── -->
            <footer class="app-footer">
                &copy; {{ date('Y') }} E-SISWI UPTM &middot; All Rights Reserved
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme + Sidebar JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;
            const themeBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const sidebar = document.getElementById('app-sidebar');
            const toggler = document.getElementById('sidebarToggler');
            const backdrop = document.getElementById('sidebarBackdrop');

            /* ── Theme ── */
            const setTheme = (theme) => {
                html.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);
                if (theme === 'dark') {
                    themeIcon.className = 'bi bi-sun-fill text-warning';
                } else {
                    themeIcon.className = 'bi bi-moon-fill';
                }
            };
            setTheme(localStorage.getItem('theme') || 'light');
            themeBtn.addEventListener('click', () => {
                setTheme(html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
            });

            /* ── Mobile Sidebar ── */
            const openSidebar = () => {
                sidebar.classList.add('sidebar-open');
                backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            };
            const closeSidebar = () => {
                sidebar.classList.remove('sidebar-open');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            };

            toggler.addEventListener('click', openSidebar);
            backdrop.addEventListener('click', closeSidebar);
            document.getElementById('sidebar-close-btn')?.addEventListener('click', closeSidebar);

            /* Close on window resize (lg+) */
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) closeSidebar();
            });
        });
    </script>
</body>

</html>