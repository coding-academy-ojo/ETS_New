<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-dark" id="sidebar">
    <div class="navbar-brand mb-4 px-3">
        <a href="/home" class="d-flex align-items-center text-white text-decoration-none">
            <img src="https://boosted.orange.com/docs/5.3/assets/brand/orange-logo.svg" width="40" height="40" alt="ETS">
            <span class="fs-4 ms-2 fw-bold">ETS</span>
        </a>
    </div>
    <hr class="text-secondary">
    <ul class="nav nav-pills flex-column mb-auto">
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
        
        <li class="nav-item mt-3 px-3">
            <span class="text-uppercase text-secondary small fw-bold">Management</span>
        </li>
        
        <li>
            <a href="{{ route('trainees.showAll') }}" class="nav-link {{ Request::is('*trainees*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                Trainees
            </a>
        </li>
        <li>
            <a href="{{ route('companies.index') }}" class="nav-link {{ Request::is('*companies*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                Companies
            </a>
        </li>
        <li>
            <a href="{{ route('survey1') }}" class="nav-link {{ Request::is('*survey*') && !Request::is('*logs*') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                Survey Manage
            </a>
        </li>
        <li>
            <a href="{{ route('survey.logs') }}" class="nav-link {{ Request::is('*survey-logs*') ? 'active' : '' }}">
                <i class="bi bi-list-task"></i>
                Survey Logs
            </a>
        </li>

        @if(auth()->check() && auth()->user()->email === 'salameh.yasin@orange.com')
        <li class="nav-item mt-3 px-3">
            <span class="text-uppercase text-secondary small fw-bold">Admin</span>
        </li>
        <li>
            <a href="{{ route('fund.manageFund') }}" class="nav-link {{ Request::is('*fund*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                Manage Funds
            </a>
        </li>
        <li>
            <a href="{{ route('user_details.manageUser') }}" class="nav-link {{ Request::is('*mangeUser*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                Manage Users
            </a>
        </li>
        @endif

        <li class="nav-item mt-3 px-3">
            <span class="text-uppercase text-secondary small fw-bold">Academies</span>
        </li>
        <li class="nav-item dropdown sidebar-dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-mortarboard"></i>
                Select Academy
            </a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="{{ url('/academy/amman') }}">Amman Academy</a></li>
                <li><a class="dropdown-item" href="{{ url('/academy/aqaba') }}">Aqaba Academy</a></li>
                <li><a class="dropdown-item" href="{{ url('/academy/zarqa') }}">Zarqa Academy</a></li>
                <li><a class="dropdown-item" href="{{ url('/academy/balqa') }}">Balqa Academy</a></li>
                <li><a class="dropdown-item" href="{{ url('/academy/irbid') }}">Irbid Academy</a></li>
                <li><a class="dropdown-item" href="{{ url('/academy/data-science') }}">Data Science</a></li>
            </ul>
        </li>
    </ul>
    <hr class="text-secondary">
    
    <div class="dropdown mt-auto px-3">
        <hr class="text-secondary">
        @auth
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <strong>{{ Auth::user()->name }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    Sign out
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
        </ul>
        @endauth
    </div>
</div>
