<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Employer Dashboard</title>

    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/css/boosted.min.css" rel="stylesheet"
          integrity="sha384-laZ3JUZ5Ln2YqhfBvadDpNyBo7w5qmWaRnnXuRwNhJeTEFuSdGbzl4ZGHAEnTozR" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/svg+xml" href="https://boosted.orange.com/docs/5.3/assets/brand/orange-logo.svg">
</head>

<body>
<div id="app" class="dashboard-wrapper">
    {{-- Sidebar (reuses the same partial, with employer branding handled via route) --}}
    @include('inc.admin-sidebar')

    {{-- Content Area --}}
    <div class="content-area">
        {{-- Top Navbar --}}
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-link btn-toggle" type="button">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h4 class="navbar-title">@yield('page_title', 'Employer Dashboard')</h4>
            </div>

            <div class="d-flex align-items-center">
                @auth('employer')
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-dark fw-bold d-flex align-items-center gap-2 py-1"
                           href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="d-none d-md-inline">{{ Auth::guard('employer')->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('employer.logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('employer.logout') }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('empLogin') }}" class="btn btn-outline-dark btn-sm">Login</a>
                @endauth
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="main-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="o-footer">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <p class="mb-0 text-muted small">&copy; Orange 2024 — ETS Employer Dashboard</p>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/js/boosted.bundle.min.js" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const appElement = document.getElementById('app');
        const sidebar = document.getElementById('sidebar');

        function toggleSidebar() {
            if (window.innerWidth > 992) {
                appElement.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', appElement.classList.contains('sidebar-collapsed'));
            } else {
                sidebar.classList.toggle('show');
            }
        }

        if (sidebarToggle && appElement && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSidebar();
            });
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', function(e) {
                e.preventDefault();
                if (window.innerWidth > 992) {
                    appElement.classList.add('sidebar-collapsed');
                    localStorage.setItem('sidebar-collapsed', 'true');
                } else {
                    sidebar.classList.remove('show');
                }
            });
        }

        if (window.innerWidth > 992 && localStorage.getItem('sidebar-collapsed') === 'true') {
            appElement.classList.add('sidebar-collapsed');
        }
    });
</script>

@stack('scripts')
</body>
</html>
