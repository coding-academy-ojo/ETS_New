@extends('layouts.app')

@section('content')
    <style>
        /* --- BRANDED ODS VARIABLES --- */
        :root {
            --ods-orange: #FF7900;
            --ods-black: #000000;
            --ods-white: #FFFFFF;
            --ods-gray-100: #F6F6F6;
            --ods-gray-200: #EEEEEE;
            --ods-gray-300: #DDDDDD;
            --ods-gray-600: #666666;
            --ods-gray-900: #141414;
            
            --ods-p-bg: #F9FAFB;
            --ods-border: #E5E7EB;
            
            --ods-success: #228722;
            --ods-success-bg: rgba(34, 135, 34, 0.1);
            --ods-danger: #CD3C14;
            --ods-danger-bg: rgba(205, 60, 20, 0.1);
        }

        .container-fluid-custom {
            padding: 2.5rem;
            background-color: var(--ods-p-bg);
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .page-header {
            margin-bottom: 2.5rem;
            border-left: 5px solid var(--ods-orange);
            padding-left: 1.5rem;
        }

        .search-card {
            background: var(--ods-white);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--ods-border);
            margin-bottom: 1.5rem;
        }

        .search-input {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--ods-border);
            padding: 0 0.75rem;
            font-size: 0.95rem; /* Larger text */
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: var(--ods-white);
            font-weight: 500;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--ods-orange);
            box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
        }

        .filter-label {
            font-size: 0.75rem; /* Slightly bigger */
            font-weight: 800;
            text-transform: uppercase;
            color: var(--ods-gray-600);
            margin-bottom: 2px;
            display: block;
            letter-spacing: 0.02em;
        }

        #page_info {
            font-size: 0.95rem; /* Matching input size */
            white-space: nowrap;
        }

        .table-container {
            background: var(--ods-white);
            border-radius: 16px;
            border: 1px solid var(--ods-border);
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            min-height: 450px;
        }

        .table-custom thead th {
            background: var(--ods-black);
            color: var(--ods-white);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .table-custom tbody td {
            padding: 1.1rem 1.5rem;
            font-size: .95rem;
            border-bottom: 1px solid var(--ods-gray-200);
            vertical-align: middle;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover {
            background-color: #FAFAFA;
        }

        .trainee-link {
            color: var(--ods-black);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .trainee-link:hover { color: var(--ods-orange); }

        .academy-badge {
            background: var(--ods-black);
            color: #fff;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: .75rem;
            font-weight: 700;
            display: inline-block;
        }

        .status-employed { background: var(--ods-success-bg); color: var(--ods-success); border: 1px solid rgba(34, 135, 34, 0.2); }
        .status-unemployed { background: var(--ods-danger-bg); color: var(--ods-danger); border: 1px solid rgba(205, 60, 20, 0.2); }
        .status-default { background: #FFF5ED; color: var(--ods-orange); border: 1px solid rgba(255, 121, 0, 0.2); }

        /* Pagination Styles */
        .pagination-container {
            margin-top: 3rem;
            gap: 0.5rem;
        }

        .page-btn {
            height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid var(--ods-border);
            cursor: pointer;
            background: var(--ods-white);
            font-weight: 600;
            color: var(--ods-gray-600);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .page-btn.active { 
            background: var(--ods-black); 
            color: var(--ods-white); 
            border-color: var(--ods-black);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .page-btn:hover:not(.active) { 
            background: var(--ods-gray-100); 
            border-color: var(--ods-gray-300);
            color: var(--ods-black);
        }

        /* Loading Area */
        #lazy-load-sentinel {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fdfdfd;
            border-top: 1px solid var(--ods-gray-100);
            transition: opacity 0.3s;
        }

        .loading-text {
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .dots:after {
            content: ' .';
            animation: dots 1.5s steps(5, end) infinite;
        }

        @keyframes dots {
            0%, 20% { color: rgba(0,0,0,0); text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0); }
            40% { color: var(--ods-orange); text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0); }
            60% { text-shadow: .25em 0 0 var(--ods-orange), .5em 0 0 rgba(0,0,0,0); }
            80%, 100% { text-shadow: .25em 0 0 var(--ods-orange), .5em 0 0 var(--ods-orange); }
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="container-fluid container-fluid-custom">
        <header class="page-header">
            <h2 class="fw-bold m-0">Trainee Records</h2>
        </header>

        {{-- SEARCH + FILTERS --}}
        <div class="search-card">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div style="flex: 1; min-width: 150px;">
                    <label class="filter-label">Keyword</label>
                    <input type="text" id="search_input" class="form-control search-input" placeholder="Name...">
                </div>
                <div style="width: 120px;">
                    <label class="filter-label">Status</label>
                    <select id="status_filter" class="form-control search-input">
                        <option value="">All</option>
                        <option value="employed">Employed</option>
                        <option value="unemployed">Unemployed</option>
                    </select>
                </div>
                <div style="width: 140px;">
                    <label class="filter-label">Academy</label>
                    <select id="academy_filter" class="form-control search-input">
                        <option value="">All</option>
                        @foreach($trainees->pluck('academy.name')->unique()->filter() as $academy)
                            <option value="{{ $academy }}">{{ $academy }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="width: 160px;">
                    <label class="filter-label">Background</label>
                    <select id="edu_back_filter" class="form-control search-input">
                        <option value="">All</option>
                        @foreach($trainees->pluck('educational_background')->unique()->filter() as $edu_background)
                            <option value="{{ $edu_background }}">{{ $edu_background }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ms-auto pt-3">
                    <div id="page_info" class="fw-bold text-dark p-2 rounded ">
                        PAGE 0 | SHOWING 0 OF 0
                    </div>
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
                <div class="loading-text text-muted small dots">SCROLLING REVEALS MORE</div>
            </div>
        </div>

        {{-- PAGINATION BUTTONS --}}
        <div class="d-flex justify-content-center">
            <div class="d-flex pagination-container" id="pagination_container"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Ensure allData is handled as an array regardless of source format
        const rawData = @json($trainees);
        const allData = Array.isArray(rawData) ? rawData : Object.values(rawData);
        let filteredData = allData.slice();

        // CONFIGURATION
        const ROWS_PER_PAGE = 20;   // Balanced page size
        const LAZY_CHUNK = 5;        // Load 5 at a time
        let ROWS_BATCH = 10;        // Initial visible items (lazy load will fill the rest of the 20)

        let currentPage = 1;        
        let currentVisibleInPage = ROWS_BATCH; 
        let isRendering = false;

        // --- CORE FUNCTIONS ---

        function applyFilters() {
            const q = document.getElementById('search_input').value.toLowerCase();
            const status = document.getElementById('status_filter').value.toLowerCase();
            const academy = document.getElementById('academy_filter').value;
            const edu = document.getElementById('edu_back_filter').value;

            filteredData = allData.filter(function(t) {
                const fullName = ((t.first_name || '') + ' ' + (t.last_name || '')).toLowerCase();
                const tStatus = (t.employment_status || '').toLowerCase();
                const tAcademy = (t.academy && t.academy.name) || '';
                const tEdu = t.educational_background || '';

                return fullName.indexOf(q) !== -1 &&
                       (!status || tStatus === status) &&
                       (!academy || tAcademy === academy) &&
                       (!edu || tEdu === edu);
            });

            currentPage = 1;
            resetLazyLoad();
            renderPagination();
            renderTable();
        }

        function resetLazyLoad() {
            currentVisibleInPage = ROWS_BATCH;
        }

        function renderTable() {
            if (isRendering) return;
            isRendering = true;

            const tbody = document.getElementById('table_body');
            const sentinel = document.getElementById('lazy-load-sentinel');
            
            const pageStart = (currentPage - 1) * ROWS_PER_PAGE;
            const pageEnd = pageStart + ROWS_PER_PAGE;
            const fullPageData = filteredData.slice(pageStart, pageEnd);
            const visibleData = fullPageData.slice(0, currentVisibleInPage);

            if (visibleData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-5">' +
                    '<div class="py-4">' +
                        '<i class="fa fa-search fa-3x text-light mb-3"></i>' +
                        '<p class="text-muted fw-bold">No records matched your criteria.</p>' +
                    '</div>' +
                '</td></tr>';
                sentinel.style.display = 'none';
                isRendering = false;
                return;
            }

            let htmlBuffer = '';
            visibleData.forEach(function(t) {
                const status = t.employment_status || 'Active';
                const sLower = status.toLowerCase();
                const statusClass = sLower === 'employed' ? 'status-employed' : (sLower === 'unemployed' ? 'status-unemployed' : 'status-default');
                const academyName = (t.academy && t.academy.name) || 'ODC';
                const cohortName = (t.cohort && t.cohort.name) || '-';
                const eduBack = (t.educational_background || 'N/A').toUpperCase();
                
                htmlBuffer += 
                '<tr class="fade-in">' +
                    '<td>' +
                        '<div class="d-flex align-items-center">' +
                             '<div class="avatar-info">' +
                                '<a href="/ets/trainees/' + t.id + '/profile" target="_blank" class="trainee-link">' + t.first_name + ' ' + t.last_name + '</a>' +
                             '</div>' +
                        '</div>' +
                    '</td>' +
                    '<td><span class="academy-badge">' + academyName + '</span></td>' +
                    '<td class="text-muted small">' + eduBack + '</td>' +
                    '<td class="text-muted fw-bold" style="font-size: 0.85rem;">' + cohortName + '</td>' +
                    '<td><span class="status-badge ' + statusClass + '">' + status + '</span></td>' +
                '</tr>';
            });

            tbody.innerHTML = htmlBuffer;

            // Update Counter
            document.getElementById('page_info').innerHTML = '<span class="text-dark">PAGE ' + currentPage + '</span> <span class="mx-2 text-muted">|</span> SHOWING ' + visibleData.length + ' OF ' + filteredData.length;

            // Hide/Show lazy sentinel
            if (currentVisibleInPage >= fullPageData.length || fullPageData.length <= ROWS_BATCH) {
                sentinel.style.opacity = '0';
                setTimeout(function() { if(sentinel.style.opacity === '0') sentinel.style.display = 'none'; }, 300);
            } else {
                sentinel.style.display = 'flex';
                setTimeout(function() { sentinel.style.opacity = '1'; }, 10);
            }

            isRendering = false;
        }

        function renderPagination() {
            const totalPages = Math.ceil(filteredData.length / ROWS_PER_PAGE);
            const container = document.getElementById('pagination_container');
            container.innerHTML = '';

            if (totalPages <= 1) {
                container.style.display = 'none';
                return;
            }
            container.style.display = 'flex';

            const createBtn = function(content, page, isActive, isDisabled) {
                const btn = document.createElement('div');
                btn.className = 'page-btn' + (isActive ? ' active' : '');
                if (isDisabled) {
                    btn.style.opacity = '0.4';
                    btn.style.pointerEvents = 'none';
                }
                btn.innerHTML = content;
                btn.onclick = function() {
                    currentPage = page;
                    resetLazyLoad();
                    renderTable();
                    renderPagination();
                    document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
                };
                return btn;
            };

            // Previous Button
            container.appendChild(createBtn('<i class="fa fa-chevron-left" style="font-size:0.8rem"></i>', currentPage - 1, false, currentPage === 1));

            // Page Numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 1) {
                    container.appendChild(createBtn(i, i, i === currentPage));
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    const dots = document.createElement('div');
                    dots.className = 'px-2 text-muted';
                    dots.innerText = '...';
                    container.appendChild(dots);
                }
            }

            // Next Button
            container.appendChild(createBtn('<i class="fa fa-chevron-right" style="font-size:0.8rem"></i>', currentPage + 1, false, currentPage === totalPages));
        }

        // --- INTERSECTION OBSERVER (THE LAZY PART) ---
        const observer = new IntersectionObserver(function(entries) {
            if (entries[0].isIntersecting && !isRendering) {
                const pageStart = (currentPage - 1) * ROWS_PER_PAGE;
                const pageEnd = pageStart + ROWS_PER_PAGE;
                const fullPageData = filteredData.slice(pageStart, pageEnd);

                if (currentVisibleInPage < fullPageData.length) {
                    currentVisibleInPage += LAZY_CHUNK;
                    renderTable();
                }
            }
        }, { threshold: 0.1 });

        // --- INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', function() {
            const sentinelEl = document.getElementById('lazy-load-sentinel');
            if (sentinelEl) observer.observe(sentinelEl);
            
            const filterIds = ['search_input', 'status_filter', 'academy_filter', 'edu_back_filter'];
            filterIds.forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', applyFilters);
                    if(id === 'search_input') el.addEventListener('input', applyFilters);
                }
            });

            renderPagination();
            renderTable();
        });
    </script>
@endsection