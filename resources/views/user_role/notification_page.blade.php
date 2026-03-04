@extends('layouts.app')

@section('content')
    <style>
        :root {
            /* Orange Design System (ODS) Color Tokens */
            --ods-orange-100: #ff7900;
            --ods-orange-200: #f16e00;
            --ods-white-100: #ffffff;
            --ods-gray-200: #eeeeee;
            --ods-gray-300: #dddddd;
            --ods-gray-400: #cccccc;
            --ods-gray-500: #999999;
            --ods-gray-600: #666666;
            --ods-gray-700: #595959;
            --ods-gray-800: #333333;
            --ods-gray-900: #141414;
            --ods-black-900: #000000;
            
            --ods-forest-100: #66cc66;
            --ods-forest-200: #228722;
            
            --ods-fire-100: #ff4d4d;
            --ods-fire-200: #cd3c14;
            
            --ods-sun-100: #ffcc00;
            --ods-sun-200: #8f7200;
        }

        body {
            background-color: #f8fafc;
            color: var(--ods-gray-800);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Table & Layout Elevation */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--ods-gray-300);
            background: white;
        }

        .table thead th {
            background-color: var(--ods-black-900) !important;
            color: var(--ods-white-100);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem 0.75rem;
            border: none;
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid var(--ods-gray-200);
            transition: background-color 0.2s ease;
        }

        .table-hover tbody tr:hover td {
            background-color: #fffaf5 !important;
        }

        .table tbody tr.read-row > td {
            background-color: #fafafa !important;
            color: var(--ods-gray-500);
        }

        .table tbody tr.read-row {
            opacity: 0.85;
        }

        .activity-row {
            cursor: pointer;
        }

        /* Status Badge Refinement */
        .badge {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .bg-success { background-color: var(--ods-forest-200) !important; }
        .bg-warning { background-color: var(--ods-sun-100) !important; color: var(--ods-black-900) !important; }
        .bg-danger { background-color: var(--ods-fire-200) !important; }

        /* Elegant Buttons */
        .btn-outline-success {
            color: var(--ods-forest-200);
            border-color: var(--ods-forest-200);
        }
        .btn-outline-success:hover {
            background-color: var(--ods-forest-200);
            color: white;
        }

        .mark-read-btn {
            background: none;
            border: none;
            padding: 0;
            font-size: 1.1rem;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .mark-read-btn:hover { opacity: 1; }

        /* Pagination Styling */
        .pagination .page-link {
            border: none;
            color: var(--ods-gray-800);
            margin: 0 4px;
            border-radius: 8px !important;
            font-weight: 500;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--ods-black-900);
            color: white;
        }

        /* Total Badge */
        #totalRecordsBadge {
            background: var(--ods-gray-200);
            color: var(--ods-gray-800);
            font-weight: 700;
            padding: 0.4em 0.8em;
        }

        /* Search Card & Filter Styling */
        .search-card {
            background: white;
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--ods-gray-300);
            margin-bottom: 1.5rem;
        }

        .filter-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--ods-gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .search-input {
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--ods-gray-300);
            padding: 0 0.75rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: white;
            font-weight: 500;
            width: 100%;
        }

        .search-input:focus {
            border-color: var(--ods-orange-100);
            box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
            outline: none;
        }
    </style>

    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0 fw-bold text-dark">
                🔔 Activity Log 
                <span id="totalRecordsBadge" class="badge rounded-pill ms-2">0 records</span>
            </h4>
            <button id="markAllBtn" class="btn btn-sm btn-outline-success px-3" data-bs-toggle="modal" data-bs-target="#confirmModal">✔ Mark all as seen</button>
        </div>

        @if($logs->count())
            <div class="search-card">
                <div class="row g-3 align-items-end">
                    <div class="col-md-auto d-flex align-items-center mb-3 mb-md-0">
                        <div class="bg-light p-2 rounded-3 me-2">
                            <i class="fa fa-filter text-primary"></i>
                        </div>
                        <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.05em;">Quick Filters</span>
                    </div>
                    <div class="col-md-3">
                        <label class="filter-label">Action Type</label>
                        <select id="actionFilter" class="form-select search-input">
                            <option value="all">All Actions</option>
                            <option value="created">Created</option>
                            <option value="updated">Updated</option>
                            <option value="deleted">Deleted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="filter-label">Performed By</label>
                        <select id="userFilter" class="form-select search-input">
                            <option value="all">All Users</option>
                            @foreach($logs->unique('user_id') as $uniqueLog)
                                <option value="{{ $uniqueLog->user ? $uniqueLog->user->name : 'System' }}">
                                    {{ $uniqueLog->user ? $uniqueLog->user->name : 'System' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover align-middle mb-0 bg-white" id="activityTable">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th class="text-center">Details</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                            @php $userName = $log->user ? $log->user->name : 'System'; @endphp
                            <tr data-id="{{ $log->id }}" data-action="{{ strtolower($log->action) }}" data-user="{{ $userName }}"
                                class="activity-row {{ $log->read == 1 ? 'read-row' : '' }}">
                                <td class="ps-3 text-muted small">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2 bg-light rounded-circle text-center"
                                            style="width:30px; height:30px; line-height:30px;">
                                            <i class="fa fa-user text-primary" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="fw-bold">{{ $userName }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill px-3 py-2 @if($log->action === 'created') bg-success @elseif($log->action === 'updated') bg-warning text-dark @else bg-danger @endif">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="text-muted fw-small" style="font-size: 0.85rem;">{{ $log->model }}</td>
                            
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light border" data-bs-toggle="modal"
                                        data-bs-target="#logModal{{ $log->id }}">
                                        <i class="fa fa-search-plus text-primary"></i> View
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button class="mark-read-btn" data-id="{{ $log->id }}">
                                        {!! $log->read == 0 ? '<span title="Mark as Read">❌</span>' : '<span title="Read">✅</span>' !!}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <ul class="pagination" id="paginationList"></ul>
            </div>

            {{-- Modals Loop --}}
            @foreach($logs as $log)
                @php $changes = json_decode($log->changes, true) ?? []; @endphp
                <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-labelledby="logModalLabel{{ $log->id }}"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                            <div class="modal-header border-bottom-0 pt-4 px-4 bg-white" style="border-radius: 16px 16px 0 0;">
                                <h5 class="modal-title fw-bold" id="logModalLabel{{ $log->id }}" style="color: var(--ods-black-900);">
                                    <span class="d-inline-block p-2 rounded-3 me-2" style="background: rgba(255, 121, 0, 0.1);">
                                        <i class="fa fa-info-circle" style="color: var(--ods-orange-100);"></i>
                                    </span>
                                    Log Reference #{{ $log->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="card border-0 mb-4" style="background: #fcfcfc; border-radius: 12px; border: 1px solid var(--ods-gray-200) !important;">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: white; border: 1px solid var(--ods-gray-300);">
                                            <i class="fa fa-user fa-lg" style="color: var(--ods-gray-600);"></i>
                                        </div>
                                        <div>
                                            <small class="text-uppercase fw-bold opacity-50" style="font-size: 0.85rem; letter-spacing: 0.1em; color: var(--ods-black-900);">
                                                @if($log->model === 'Company')
                                                    Company Name
                                                @elseif($log->model === 'Trainee' || $log->model === 'EmploymentLog')
                                                    Trainee Name
                                                @else
                                                    {{ $log->model }} 
                                                @endif
                                            </small>
                                            <h4 class="mb-0 fw-bold" style="color: var(--ods-black-900);">
                                                @if($log->model === 'EmploymentLog' || $log->model === 'Trainee')
                                                    {{ $changes['trainee_name'] ?? ($log->trainee ? $log->trainee->first_name . ' ' . $log->trainee->last_name : 'N/A') }}
                                                @elseif($log->model === 'Company')
                                                    {{ $changes['company_name'] ?? ($log->company ? $log->company->company_name : 'N/A') }}
                                                @else
                                                    {{ $log->model }} Reference
                                                @endif
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive bg-transparent border-0">
                                    <table class="table table-sm border-0 mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-3 py-2 text-light bg-light border-0 fw-bold" style="font-size: 0.9rem; border-radius: 8px 0 0 8px;">Property Field</th>
                                                <th class="py-2 text-light bg-light border-0 fw-bold" style="font-size: 0.9rem; border-radius: 0 8px 8px 0;">Observation / Value</th>
                                            </tr>
                                        </thead>
                                        <tbody class="align-middle">
                                            @forelse($changes as $field => $value)
                                                @if($field === 'trainee_name' || $field === 'created_at') @continue @endif
                                                <tr>
                                                    <td class="ps-3 fw-bold text-dark py-3" style="width: 200px; font-size: 0.95rem;">
                                                        {{ str_replace('_', ' ', strtoupper($field)) }}
                                                    </td>
                                                    <td class="py-3" style="font-size: 0.95rem;">
                                                        @if(is_array($value))
                                                            <div class="d-flex flex-column gap-1">
                                                                @foreach($value as $k => $v)
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <span class="badge bg-light text-dark border fw-normal" style="font-size: 0.8rem;">{{ str_replace('_', ' ', $k) }}</span>
                                                                        <span class="text-muted">{{ is_array($v) ? json_encode($v) : $v }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-muted">{{ $value ?? 'N/A' }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="p-5 text-center text-muted fst-italic">No granular changes were detected for this log.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pb-4 px-4">
                                <!-- <button type="button" class="btn btn-dark fw-bold px-4" style="border-radius: 8px;" data-bs-dismiss="modal">Acknowledge</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        {{-- Success Modal --}}
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                    <div class="modal-body text-center p-5">
                        <div class="mb-4 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: rgba(34, 135, 34, 0.1);">
                            <i class="fa fa-check-circle fa-3x" style="color: var(--ods-forest-200);"></i>
                        </div>
                        <h4 id="successModalMessage" class="fw-bold mb-2" style="color: var(--ods-black-900);">Successful Update</h4>
                        <p class="text-muted mb-4">All unread notifications have been marked as seen and counts updated.</p>
                        <button type="button" class="btn btn-dark fw-bold px-5 py-2" style="border-radius: 8px;" data-bs-dismiss="modal">Excellent</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Confirmation Modal --}}
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                    <div class="modal-header border-bottom-0 pt-4 px-4 bg-white" style="border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title fw-bold" id="confirmModalLabel" style="color: var(--ods-black-900);">
                            <span class="d-inline-block p-2 rounded-3 me-2" style="background: rgba(255, 204, 0, 0.1);">
                                <i class="fa fa-exclamation-triangle" style="color: var(--ods-sun-100);"></i>
                            </span>
                            Bulk Action
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <h4 class="fw-bold mb-2" style="color: var(--ods-black-900);">Acknowledge All?</h4>
                        <p class="text-muted mb-0">This will mark all activity logs as seen across your entire history.</p>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-center pb-5 pt-2 px-4">
                        <button type="button" class="btn btn-light px-4 py-2 border fw-semibold" style="border-radius: 8px; ;" data-bs-dismiss="modal">Dismiss</button>
                        <button type="button" id="confirmMarkAllBtn" class="btn btn-dark px-4 py-2 fw-bold" style="border-radius: 8px; ;">Yes, Mark All</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('🔧 Activity Log Script Loaded');

            const rowsPerPage = 20;
            let currentPage = 1;

            const tableBody = document.querySelector('#activityTable tbody');
            const allRows = Array.from(tableBody.querySelectorAll('.activity-row'));
            const actionFilter = document.getElementById('actionFilter');
            const userFilter = document.getElementById('userFilter');
            const paginationList = document.getElementById('paginationList');
            const totalRecordsBadge = document.getElementById('totalRecordsBadge');

            console.log('📊 Total Rows Found:', allRows.length);

            let filteredRows = allRows;

            function applyFilters() {
                const actionVal = actionFilter.value.toLowerCase();
                const userVal = userFilter.value;

                filteredRows = allRows.filter(row => {
                    const matchesAction = (actionVal === 'all' || row.dataset.action === actionVal);
                    const matchesUser = (userVal === 'all' || row.dataset.user === userVal);
                    return matchesAction && matchesUser;
                });

                currentPage = 1;
                updateTableUI();
            }

            function updateTableUI() {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                allRows.forEach(row => row.style.display = 'none');
                filteredRows.slice(start, end).forEach(row => row.style.display = '');

                totalRecordsBadge.textContent = `${filteredRows.length} ${filteredRows.length === 1 ? 'record' : 'records'}`;

                renderPagination();
            }

            function renderPagination() {
                paginationList.innerHTML = '';
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (totalPages <= 1) return;

                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    li.onclick = (e) => { e.preventDefault(); currentPage = i; updateTableUI(); };
                    paginationList.appendChild(li);
                }
            }

            actionFilter.addEventListener('change', applyFilters);
            userFilter.addEventListener('change', applyFilters);

            // ✅ Mark Single Row (Toggle)
            const markReadButtons = document.querySelectorAll('.mark-read-btn');
            console.log('🔘 Mark Read Buttons Found:', markReadButtons.length);

            markReadButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();

                    const id = this.dataset.id;
                    const button = this;

                    console.log('🔄 Toggling log ID:', id);

                    fetch(`/ets/activity-log/${id}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => {
                            console.log('📡 Response Status:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('✅ Response Data:', data);

                            if (data.success) {
                                // Update button with BOTH emoji AND title attribute
                                if (data.read) {
                                    button.innerHTML = '<span title="Read">✅</span>';
                                    button.closest('tr').classList.add('read-row');
                                    console.log('✅ Marked as READ (ID:', id, ')');
                                } else {
                                    button.innerHTML = '<span title="Mark as Read">❌</span>';
                                    button.closest('tr').classList.remove('read-row');
                                    console.log('❌ Marked as UNREAD (ID:', id, ')');
                                }

                                // Update Navbar Badge Live
                                const navbarBadge = document.getElementById('navbarUnreadBadge');
                                if (navbarBadge) {
                                    navbarBadge.textContent = data.unreadCount;
                                    navbarBadge.style.display = (data.unreadCount == 0) ? 'none' : '';
                                }
                            } else {
                                console.error('❌ Success flag is false');
                            }
                        })
                        .catch(error => {
                            console.error('❌ Fetch Error:', error);
                            alert('Failed to update status. Check console for details.');
                        });
                });
            });

            // ✅ Mark All as Read
            const markAllBtn = document.getElementById('markAllBtn');
            console.log('🔘 Mark All Button:', markAllBtn ? 'Found' : 'NOT FOUND');

            if (markAllBtn) {
                const confirmBtn = document.getElementById('confirmMarkAllBtn');
                const confirmModalEl = document.getElementById('confirmModal');

                confirmBtn.addEventListener('click', function () {
                    const confirmModal = boosted.Modal.getInstance(confirmModalEl) || new boosted.Modal(confirmModalEl);
                    confirmModal.hide();
                    console.log('🔄 Marking all as read...');

                    fetch(`/ets/activity-log/mark-all-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => {
                            console.log('📡 Response Status:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('✅ Response Data:', data);

                            if (data.success) {
                                // Update ALL rows with proper title attribute
                                allRows.forEach(row => {
                                    row.classList.add('read-row');
                                    const btn = row.querySelector('.mark-read-btn');
                                    if (btn) {
                                        btn.innerHTML = '<span title="Read">✅</span>';
                                    }
                                });

                                // Update Navbar Badge Live
                                const navbarBadge = document.getElementById('navbarUnreadBadge');
                                if (navbarBadge) {
                                    navbarBadge.textContent = '0';
                                    navbarBadge.style.display = 'none';
                                }

                                console.log('✅ All logs marked as read!');

                                // Show Success Modal instead of Alert
                                const successMsg = `All logs marked as seen! (${data.updated_count || 'All'} updated)`;
                                document.getElementById('successModalMessage').textContent = successMsg;
                                const successModalEl = document.getElementById('successModal');
                                const successModal = boosted.Modal.getInstance(successModalEl) || new boosted.Modal(successModalEl);
                                successModal.show();
                            }
                        })
                        .catch(error => {
                            console.error('❌ Fetch Error:', error);

                        });
                });
            }

            updateTableUI();
        });
    </script>
@endsection