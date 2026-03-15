<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Employer Dashboard</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link href="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/css/boosted.min.css" rel="stylesheet"
        integrity="sha384-laZ3JUZ5Ln2YqhfBvadDpNyBo7w5qmWaRnnXuRwNhJeTEFuSdGbzl4ZGHAEnTozR" crossorigin="anonymous">

    <style>
        .sidebar { min-height: 100vh; }
        .sidebar .nav-link.active { background-color: #ff7900 !important; }
        .top-navbar { height: 70px; border-bottom: 1px solid #dee2e6; }
        .main-content { padding: 20px; }
    </style>
</head>

<body>
<div id="app" class="dashboard-wrapper">
    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-dark" id="sidebar">
        <div class="navbar-brand mb-4 px-3">
            <a href="{{route('employer.dashboard')}}" class="d-flex align-items-center text-white text-decoration-none">
                <img src="https://boosted.orange.com/docs/5.3/assets/brand/orange-logo.svg" width="40" height="40" alt="ETS">
                <span class="fs-4 ms-2 fw-bold">Employer</span>
            </a>
        </div>
        <hr class="text-secondary">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{route('employer.dashboard')}}" class="nav-link {{ Request::is('*dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Home
                </a>
            </li>
            <li>
                <a href="{{route('employer.shortList')}}" class="nav-link {{ Request::is('*shortList*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i>
                    Shortlisted ({{$numberOfTrainees}})
                </a>
            </li>
        </ul>
        <hr class="text-secondary">
    </div>

    {{-- Content Area --}}
    <div class="content-area">
        {{-- Top Navbar --}}
        <nav class="top-navbar bg-white d-flex align-items-center justify-content-between px-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-link link-dark d-lg-none me-2" type="button" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <h4 class="mb-0 text-dark fw-bold">@yield('page_title', 'Employer Dashboard')</h4>
            </div>

            <div class="d-flex align-items-center">
                @auth('employer')
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-dark fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-4 me-1"></i>
                            {{ Auth::guard('employer')->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
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
                    <a href="{{ route('empLogin') }}" class="btn btn-outline-dark">Login</a>
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
        <footer class="o-footer p-4 border-top">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <p class="mb-0 text-muted">© Orange 2024 - ETS Employer Dashboard</p>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/js/boosted.bundle.min.js" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
