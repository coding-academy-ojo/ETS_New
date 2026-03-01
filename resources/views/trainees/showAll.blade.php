@extends('layouts.app')

@section('content')
    <style>
        /* --- BRANDED UX VARIABLES --- */
        :root {
            --orange-brand: #FF7900;
            --charcoal: #2D2D2D;
            --bg-body: #F9FAFB;
            --border-soft: #E5E7EB;
            --orange-subtle: #FFF5ED;
            --success-bg: #ECFDF5;
            --success-text: #059669;
            --danger-bg: #FEF2F2;
            --danger-text: #DC2626;
        }

        .container-fluid-custom {
            padding: 2rem;
            background-color: var(--bg-body);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .page-header {
            margin-bottom: 2rem;
            border-left: 4px solid var(--orange-brand);
            padding-left: 1.5rem;
        }

        .search-card {
            background: #ffffff;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid var(--border-soft);
            margin-bottom: 1.5rem;
        }

        .search-input {
            height: 48px;
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            padding: 0 0.75rem;
            font-size: 0.85rem;
        }

        .table-container {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border-soft);
            overflow: hidden;
            min-height: 400px;
        }

        .table-custom thead th {
            background: #FBFBFC;
            font-size: .75rem;
            text-transform: uppercase;
            padding: 1rem 1.5rem;
        }

        .table-custom tbody td {
            padding: 1rem 1.5rem;
            font-size: .9rem;
        }

        .trainee-link {
            color: var(--charcoal);
            text-decoration: none;
            font-weight: 600;
        }

        .trainee-link:hover { color: var(--orange-brand); }

        .academy-badge {
            background: var(--charcoal);
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: .7rem;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: .75rem;
            font-weight: 600;
        }

        .status-employed { background: var(--success-bg); color: var(--success-text); }
        .status-unemployed { background: var(--danger-bg); color: var(--danger-text); }
        .status-default { background: var(--orange-subtle); color: var(--orange-brand); }

        /* Pagination Styles */
        .page-btn {
            height: 40px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            cursor: pointer;
            background: #fff;
            font-weight: 500;
            transition: all 0.2s;
        }

        .page-btn.active { background: var(--orange-brand); color: #fff; border-color: var(--orange-brand); }
        .page-btn:hover:not(.active) { background: #f3f4f6; }

        /* Lazy Load Loading Area */
        #lazy-load-sentinel {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top: 1px solid #f9f9f9;
        }

        .dots:after {
            content: ' .';
            animation: dots 1s steps(5, end) infinite;
        }

        @keyframes dots {
            0%, 20% { color: rgba(0,0,0,0); text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0); }
            40% { color: var(--orange-brand); text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0); }
            60% { text-shadow: .25em 0 0 var(--orange-brand), .5em 0 0 rgba(0,0,0,0); }
            80%, 100% { text-shadow: .25em 0 0 var(--orange-brand), .5em 0 0 var(--orange-brand); }
        }
    </style>

    <div class="container-fluid container-fluid-custom">
        <header class="page-header">
            <h2 class="fw-bold m-0">Trainee Records</h2>
            <p class="text-muted small">Hybrid Pagination & Lazy Loading</p>
        </header>

        {{-- SEARCH + FILTERS --}}
        <div class="search-card">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1 d-block">Keyword</label>
                    <input type="text" id="search_input" class="form-control search-input" placeholder="Search name...">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted mb-1 d-block">Status</label>
                    <select id="status_filter" class="form-control search-input">
                        <option value="">All Statuses</option>
                        <option value="employed">Employed</option>
                        <option value="unemployed">Unemployed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted mb-1 d-block">Academy</label>
                    <select id="academy_filter" class="form-control search-input">
                        <option value="">All Academies</option>
                        @foreach($trainees->pluck('academy.name')->unique()->filter() as $academy)
                            <option value="{{ $academy }}">{{ $academy }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1 d-block">Background</label>
                    <select id="edu_back_filter" class="form-control search-input">
                        <option value="">All Backgrounds</option>
                        @foreach($trainees->pluck('educational_background')->unique()->filter() as $edu_background)
                            <option value="{{ $edu_background }}">{{ $edu_background }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-md-end align-self-end">
                    <div id="page_info" class="small fw-bold text-muted">Showing 0 of 0</div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Academy</th>
                            <th>Background</th>
                            <th>Cohort</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="table_body"></tbody>
                </table>
            </div>

            {{-- Lazy Loading Trigger --}}
            <div id="lazy-load-sentinel">
                <span class="text-muted small dots">Scrolling reveals more</span>
            </div>
        </div>

        {{-- PAGINATION BUTTONS --}}
        <div class="d-flex justify-content-center mt-4">
            <div class="d-flex gap-2" id="pagination_container"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const allData = @json($trainees);
        let filteredData = [...allData];

        // CONFIGURATION
        const ROWS_PER_PAGE = 20;   // Total rows per pagination click
        const LAZY_CHUNK = 5;       // Rows to show per scroll

        let currentPage = 1;        // Current active page
        let currentVisibleInPage = LAZY_CHUNK; // How many rows are currently unhidden via scroll

        // --- CORE FUNCTIONS ---

        function applyFilters() {
            const q = document.getElementById('search_input').value.toLowerCase();
            const status = document.getElementById('status_filter').value.toLowerCase();
            const academy = document.getElementById('academy_filter').value;
            const edu = document.getElementById('edu_back_filter').value;

            filteredData = allData.filter(t => {
                const fullName = `${t.first_name} ${t.last_name}`.toLowerCase();
                return fullName.includes(q) &&
                       (!status || (t.employment_status ?? '').toLowerCase() === status) &&
                       (!academy || (t.academy?.name ?? '') === academy) &&
                       (!edu || (t.educational_background ?? '') === edu);
            });

            currentPage = 1;
            resetLazyLoad();
            renderPagination();
            renderTable();
        }

        function resetLazyLoad() {
            currentVisibleInPage = LAZY_CHUNK;
        }

        function renderTable() {
            const tbody = document.getElementById('table_body');
            const sentinel = document.getElementById('lazy-load-sentinel');
            
            // Calculate slice for current page
            const pageStart = (currentPage - 1) * ROWS_PER_PAGE;
            const pageEnd = pageStart + ROWS_PER_PAGE;
            const fullPageData = filteredData.slice(pageStart, pageEnd);

            // Calculate subset for Lazy Loading
            const visibleData = fullPageData.slice(0, currentVisibleInPage);

            tbody.innerHTML = '';
            
            if (visibleData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5">No records found.</td></tr>`;
                sentinel.style.display = 'none';
                return;
            }

            visibleData.forEach(t => {
                const status = t.employment_status ?? 'Active';
                const statusClass = status.toLowerCase() === 'employed' ? 'status-employed' : (status.toLowerCase() === 'unemployed' ? 'status-unemployed' : 'status-default');
                
                tbody.innerHTML += `
                <tr>
                    <td><a href="/ets/trainees/${t.id}/profile" target="_blank" class="trainee-link">${t.first_name} ${t.last_name}</a></td>
                    <td><span class="academy-badge">${t.academy?.name ?? 'ODC'}</span></td>
                    <td class="text-muted small">${(t.educational_background ?? 'N/A').toUpperCase()}</td>
                    <td class="text-muted">${t.cohort?.name ?? '-'}</td>
                    <td><span class="status-badge ${statusClass}">${status}</span></td>
                </tr>`;
            });

            // Update Counter
            document.getElementById('page_info').innerText = `Page ${currentPage}: Showing ${visibleData.length} of ${fullPageData.length} (Total: ${filteredData.length})`;

            // Hide/Show lazy sentinel
            if (currentVisibleInPage >= fullPageData.length) {
                sentinel.style.display = 'none';
            } else {
                sentinel.style.display = 'flex';
            }
        }

        function renderPagination() {
            const totalPages = Math.ceil(filteredData.length / ROWS_PER_PAGE);
            const container = document.getElementById('pagination_container');
            container.innerHTML = '';

            if (totalPages <= 1) return;

            // Simple Pagination Buttons
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 1) {
                    const btn = document.createElement('div');
                    btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                    btn.innerText = i;
                    btn.onclick = () => {
                        currentPage = i;
                        resetLazyLoad();
                        renderTable();
                        renderPagination();
                        window.scrollTo({top: 0, behavior: 'smooth'});
                    };
                    container.appendChild(btn);
                }
            }
        }

        // --- INTERSECTION OBSERVER (THE LAZY PART) ---
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                const pageStart = (currentPage - 1) * ROWS_PER_PAGE;
                const pageEnd = pageStart + ROWS_PER_PAGE;
                const fullPageData = filteredData.slice(pageStart, pageEnd);

                if (currentVisibleInPage < fullPageData.length) {
                    // Load next chunk
                    setTimeout(() => {
                        currentVisibleInPage += LAZY_CHUNK;
                        renderTable();
                    }, 200);
                }
            }
        }, { threshold: 0.1 });

        // --- INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', () => {
            observer.observe(document.getElementById('lazy-load-sentinel'));
            
            ['search_input', 'status_filter', 'academy_filter', 'edu_back_filter'].forEach(id => {
                document.getElementById(id).addEventListener('change', applyFilters);
                if(id === 'search_input') document.getElementById(id).addEventListener('input', applyFilters);
            });

            renderPagination();
            renderTable();
        });
    </script>
@endsection