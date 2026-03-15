<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ETS - Dashboard</title>

    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link href="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/css/boosted.min.css" rel="stylesheet"
          integrity="sha384-laZ3JUZ5Ln2YqhfBvadDpNyBo7w5qmWaRnnXuRwNhJeTEFuSdGbzl4ZGHAEnTozR" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/62ca34cbb0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/svg+xml" href="https://boosted.orange.com/docs/5.3/assets/brand/orange-logo.svg">

    <style>
        .sidebar { min-height: 100vh; }
        .sidebar .nav-link.active { background-color: #ff7900 !important; }
        .top-navbar { height: 70px; border-bottom: 1px solid #dee2e6; }
        .main-content { padding: 20px; }
        .sidebar-dropdown .dropdown-menu { position: static !important; transform: none !important; margin-left: 20px !important; background: transparent; border: none; }
        .sidebar-dropdown .dropdown-item { color: #ccc; }
        .sidebar-dropdown .dropdown-item:hover { background-color: rgba(255,121,0,0.2); color: #fff; }
    </style>
</head>

<body>
<div id="app" class="dashboard-wrapper">
    {{-- Sidebar --}}
    @include('inc.admin-sidebar')

    {{-- Content Area --}}
    <div class="content-area">
        {{-- Top Navbar --}}
        <nav class="top-navbar bg-white d-flex align-items-center justify-content-between px-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-link link-dark d-lg-none me-2" type="button" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <h4 class="mb-0 text-dark fw-bold">@yield('page_title', 'Dashboard')</h4>
            </div>

            <div class="d-flex align-items-center">
                {{-- Notifications --}}
                @if(auth()->check() && auth()->user()->email === 'salameh.yasin@orange.com')
                    <div class="me-3 position-relative">
                        <a href="{{ route('user_notification') }}" class="text-dark">
                            <i class="bi bi-bell fs-4"></i>
                            <span id="navbarUnreadBadge"
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="{{ $unreadActivityCount == 0 ? 'display:none' : '' }}">
                                {{ $unreadActivityCount }}
                            </span>
                        </a>
                    </div>
                @endif

                {{-- User Profile --}}
                @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-dark fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-4 me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-dark">Register</a>
                @endauth
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="main-content">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="o-footer p-4 border-top">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <p class="mb-0 text-muted">© Orange 2024 - ETS Dashboard</p>
                <div class="footer-links">
                    <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/js/boosted.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/noframework.waypoints.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js'></script>
<script src="{{ asset('script.js') }}"></script>
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

@yield('scripts')
</body>
</html>
