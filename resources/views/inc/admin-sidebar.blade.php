<div class="sidebar d-flex flex-column flex-shrink-0 bg-dark" id="sidebar">
    {{-- Brand Header --}}
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <a href="{{ Auth::guard('employer')->check() ? route('employer.dashboard') : '/home' }}"
           class="d-flex align-items-center text-white text-decoration-none gap-2">
            <img src="https://boosted.orange.com/docs/5.3/assets/brand/orange-logo.svg" class="brand-logo" alt="ETS">
            <span class="brand-text">{{ Auth::guard('employer')->check() ? 'Employer' : 'ETS' }}</span>
        </a>
        <button id="sidebarClose" class="btn btn-link text-white p-1 border-0 opacity-75" type="button" aria-label="Close sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <ul class="nav nav-pills flex-column mb-0">
        @auth('employer')
            {{-- Employer Nav --}}
            <li class="nav-item">
                <a href="{{ route('employer.dashboard') }}" class="nav-link {{ Request::is('*dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('employer.shortList') }}" class="nav-link {{ Request::is('*shortList*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i>
                    Shortlisted
                </a>
            </li>
        @else
            {{-- Admin Nav --}}
            <li class="nav-item">
                <a href="/home" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('statistics.dashboard') }}" class="nav-link {{ Request::is('*statistics*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    Statistics
                </a>
            </li>

            <li class="nav-item dropdown sidebar-dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-mortarboard"></i>
                    Select Academy
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ url('/academy/amman') }}">Amman Academy</a></li>
                    <li><a class="dropdown-item" href="{{ url('/academy/aqaba') }}">Aqaba Academy</a></li>
                    <li><a class="dropdown-item" href="{{ url('/academy/zarqa') }}">Zarqa Academy</a></li>
                    <li><a class="dropdown-item" href="{{ url('/academy/balqa') }}">Balqa Academy</a></li>
                    <li><a class="dropdown-item" href="{{ url('/academy/irbid') }}">Irbid Academy</a></li>
                    <li><a class="dropdown-item" href="{{ url('/academy/data-science') }}">Data Science</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('trainees.showAll') }}" class="nav-link {{ Request::is('*trainees*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Trainees
                </a>
            </li>

            <li class="nav-item dropdown sidebar-dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('*companies*') || Request::is('*company-statistics*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-building"></i>
                    Companies
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item {{ Request::is('*companies*') ? 'active' : '' }}" href="{{ route('companies.index') }}">Company Insight</a></li>
                    <li><a class="dropdown-item {{ Request::is('*company-statistics*') ? 'active' : '' }}" href="{{ route('companies.statistics') }}">Company Statistics</a></li>
                </ul>
            </li>

            @if(auth()->check() && auth()->user()->email === 'salameh.yasin@orange.com')
            <li class="nav-item">
                <a href="{{ route('fund.manageFund') }}" class="nav-link {{ Request::is('*fund*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i>
                    Manage Funds
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user_details.manageUser') }}" class="nav-link {{ Request::is('*mangeUser*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    Manage Users
                </a>
            </li>
            @endif
        @endauth
    </ul>

    {{-- User Area --}}
    <div class="sidebar-user">
        @auth('employer')
        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5"></i>
                <strong class="text-truncate">{{ Auth::guard('employer')->user()->name }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('employer.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Sign out
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('employer.logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        @else
            @auth
            <div class="dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5"></i>
                    <strong class="text-truncate">{{ Auth::user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Sign out
                        </a>
                        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        @endauth
    </div>
</div>
