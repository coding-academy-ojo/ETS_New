<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ETS</title>

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


    <!-- Orange Boosted is already included above (Line 18 & 157), no need for standard bootstrap -->
    <style>
        .map-orange { margin: 50px auto; position: relative; width: 200px; }
    </style>
</head>

<body>
<div id="app">
    <header data-bs-theme="dark">
        <nav class="navbar navbar-expand-lg bg-dark">
            <div class="container-xxl">

                {{-- Brand --}}
                <div class="navbar-brand me-lg-4">
                    <a href="#">
                        <img src="https://boosted.orange.com/docs/5.3/assets/brand/orange-logo.svg" width="50" height="50" alt="ETS">
                    </a>
                    <h1 class="title d-inline text-white ms-2">ETS</h1>
                </div>

                {{-- Toggler --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                {{-- LEFT SIDE --}}
                <div class="collapse navbar-collapse d-flex justify-content-between w-100" id="mainNavbar">

                    {{-- LEFT SIDE --}}
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>

                        {{-- Manage --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manage</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('survey1') }}">Survey Manage</a></li>
                                <li><a class="dropdown-item" href="{{ route('survey.logs') }}">Survey Logs</a></li>
                                <li><a class="dropdown-item" href="{{ route('trainees.showAll') }}">Trainees</a></li>
                                <li><a class="dropdown-item" href="{{ route('companies.index') }}">Companies</a></li>
                                @if(auth()->check() && auth()->user()->email === 'salameh.yasin@orange.com')
                                    <li><a class="dropdown-item" href="{{ route('fund.manageFund') }}">Manage Funds</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user_details.manageUser') }}">Manage Users</a></li>
                                @endif
                            </ul>
                        </li>

                        {{-- Academy --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Select Academy</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/academy/amman') }}">Amman Academy</a></li>
                                <li><a class="dropdown-item" href="{{ url('/academy/aqaba') }}">Aqaba Academy</a></li>
                                <li><a class="dropdown-item" href="{{ url('/academy/zarqa') }}">Zarqa Academy</a></li>
                                <li><a class="dropdown-item" href="{{ url('/academy/balqa') }}">Balqa Academy</a></li>
                                <li><a class="dropdown-item" href="{{ url('/academy/irbid') }}">Irbid Academy</a></li>
                                <li><a class="dropdown-item" href="{{ url('/academy/data-science') }}">Data Science</a></li>
                            </ul>
                        </li>

                        {{-- Auth --}}
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                    {{-- RIGHT SIDE: Notification --}}
                    @if(auth()->check() && auth()->user()->email === 'salameh.yasin@orange.com')
                        <ul class="navbar-nav">
                            <li class="nav-item position-relative">
                                <a class="nav-link" href="{{ route('user_notification') }}">
                                    <i class="bi bi-bell-fill fs-5 text-warning"></i>
                                    <span id="navbarUnreadBadge"
                                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                          style="{{ $unreadActivityCount == 0 ? 'display:none' : '' }}">
                      {{ $unreadActivityCount }}
                </span>
                                </a>
                            </li>
                        </ul>
                    @endif



                </div>


            </div>
        </nav>
    </header>

    <main class="container py-5 mt-3">
        @yield('content')
    </main>
</div>

<footer class="o-footer p-5 bg-dark text-light bottom-0 w-100">
    <div class="o-footer-bottom">
        <div class="container-fluid">
            <p class="my-2">© Orange 2024</p>
        </div>
    </div>
</footer>

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
