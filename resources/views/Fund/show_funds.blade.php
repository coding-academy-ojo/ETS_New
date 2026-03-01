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
        }

        .page-header {
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--ods-black-900);
            padding-bottom: 1rem;
        }

        .page-title {
            font-weight: 700;
            color: var(--ods-black-900);
            text-transform: uppercase;
            letter-spacing: -0.02em;
            margin: 0;
        }

        /* Elevated Card Container */
        .content-card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--ods-gray-300);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            padding: 1.5rem;
        }

        /* Table Styling */
        .table-container {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--ods-gray-200);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--ods-black-900) !important;
            color: var(--ods-white-100);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 1.25rem 1rem;
            border: none;
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--ods-gray-200);
            color: var(--ods-gray-800);
            font-size: 0.9rem;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover td {
            background-color: #fffaf5 !important;
        }

        /* Badge Styling */
        .badge {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.65rem;
            padding: 0.5em 1em;
            letter-spacing: 0.05em;
            border-radius: 6px;
        }

        /* Button Styling */
        .btn-create {
            background-color: var(--ods-orange-100);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-create:hover {
            background-color: var(--ods-orange-200);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(255, 121, 0, 0.2);
        }

        .btn-action {
            font-weight: 600;
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .btn-edit {
            color: var(--ods-forest-200);
            border: 1px solid var(--ods-forest-200);
            background: transparent;
        }

        .btn-edit:hover {
            background: var(--ods-forest-200);
            color: white;
        }

        .btn-show {
            color: var(--ods-orange-100);
            border: 1px solid var(--ods-orange-100);
            background: transparent;
        }

        .btn-show:hover {
            background: var(--ods-orange-100);
            color: white;
        }
    </style>

    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="background-color: #e6f4ea; color: #1e7e34; border-left: 5px solid #228722 !important; border-radius: 8px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="page-header d-flex justify-content-between align-items-center">
            <h2 class="page-title">Fund Management</h2>
            <a href="{{ route('fund.createFund') }}" class="btn btn-create">
                <i class="fas fa-plus me-2"></i> Create New Fund
            </a>
        </div>

        <div class="content-card">
            <div class="table-responsive table-container">
                <table id="funds-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Fund Name</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">Period</th>
                            <th scope="col">Expiry Date</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($funds as $fund)
                            <tr>
                                <td class="fw-bold text-dark">{{ $fund->fund_name }}</td>
                                <td>
                                    <span class="text-muted"><i class="far fa-calendar-alt me-2"></i>{{ $fund->start_date }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-normal">{{ $fund->period }}</span>
                                </td>
                                <td>
                                    @if ($fund->end_date)
                                        <span class="text-muted"><i class="far fa-calendar-check me-2"></i>{{ $fund->end_date }}</span>
                                    @else
                                        <span class="badge bg-light text-dark border fw-normal">Present</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColor = $fund->status === 'Active' ? 'var(--ods-forest-200)' : 'var(--ods-fire-200)';
                                    @endphp
                                    <span class="badge text-white" style="background-color: {{ $statusColor }}; min-width: 80px;">
                                        {{ $fund->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('fund.fund_update_info', [$fund->id]) }}"
                                            class="btn btn-action btn-edit" title="Edit Fund">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <a href="{{ route('fund.show_cohort_related_fund', [$fund->id]) }}"
                                            class="btn btn-action btn-show" title="View Cohorts">
                                            <i class="fas fa-eye me-1"></i> Show
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
