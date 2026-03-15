@extends('layouts.app')

@section('content')
    <style>
        /* --- BRANDED ODS VARIABLES --- */
        :root {
            /* --- BRANDED ODS VARIABLES (Official Boosted Palette) --- */
            --ods-orange-100: #FF7900;
            --ods-orange-200: #F16E00;
            --ods-white:      #FFFFFF;
            --ods-black:      #000000;
            
            /* Grays */
            --ods-gray-100:   #FAFAFA;
            --ods-gray-200:   #F6F6F6;
            --ods-gray-300:   #EEEEEE;
            --ods-gray-400:   #DDDDDD;
            --ods-gray-500:   #CCCCCC;
            --ods-gray-600:   #999999;
            --ods-gray-700:   #666666;
            --ods-gray-800:   #595959;
            --ods-gray-900:   #333333;
            --ods-gray-950:   #141414;
            
            /* Backgrounds & Borders */
            --ods-p-bg:       #F9FAFB;
            --ods-border:     var(--ods-gray-400);
            
            /* Functional */
            --ods-success:    #228722; /* $ods-forest-200 */
            --ods-danger:     #CD3C14; /* $ods-fire-200 */
        }


        .page-header {
            margin-bottom: 2.5rem;
            border-left: 5px solid var(--ods-orange-100);
            padding-left: 1.5rem;
        }

        .table-container {
            background: var(--ods-white);
            border-radius: 16px;
            border: 1px solid var(--ods-border);
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            padding: 0; /* Override padding for fund card */
        }

        .table-custom thead th {
            background: var(--ods-black) !important;
            color: var(--ods-white) !important;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .table-custom tbody td {
            padding: 1.1rem 1.5rem;
            font-size: .95rem;
            border-bottom: 1px solid var(--ods-gray-300);
            vertical-align: middle;
            color: var(--ods-gray-900);
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover td {
            background-color: var(--ods-gray-100) !important;
        }

        /* Badge Styling */
        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: .75rem;
            font-weight: 700;
            display: inline-block;
            min-width: 90px;
            text-align: center;
        }

        .status-active { background: rgba(34, 135, 34, 0.1); color: var(--ods-success); border: 1px solid rgba(34, 135, 34, 0.2); }
        .status-expired { background: rgba(205, 60, 20, 0.1); color: var(--ods-danger); border: 1px solid rgba(205, 60, 20, 0.2); }

        /* Button Styling */
        .btn-ods-orange {
            background-color: var(--ods-orange-100);
            color: white;
            font-weight: 700;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-ods-orange:hover {
            background-color: var(--ods-orange-200);
            color: white;
            box-shadow: 0 4px 12px rgba(255, 121, 0, 0.2);
        }

        .btn-icon {
            width: 38px;
            height: 38px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-edit {
            color: var(--ods-success);
            border: 1px solid rgba(34, 135, 34, 0.2);
            background: rgba(34, 135, 34, 0.05);
        }

        .btn-edit:hover {
            background: var(--ods-success);
            color: white;
        }

        .btn-show {
            color: var(--ods-orange-100);
            border: 1px solid rgba(255, 121, 0, 0.2);
            background: rgba(255, 121, 0, 0.05);
        }

        .btn-show:hover {
            background: var(--ods-orange-100);
            color: white;
        }
    </style>

    <div class="">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="background-color: #e6f4ea; color: #1e7e34; border-left: 5px solid var(--ods-success) !important; border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <header class="page-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold m-0">Fund Management</h2>
            <a href="{{ route('fund.createFund') }}" class="btn btn-ods-orange fw-bold">
                <i class="fas fa-plus me-2"></i>Create New Fund
            </a>
        </header>

        <div class="table-container">
            <div class="table-responsive">
                <table id="funds-table" class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Fund Name</th>
                            <th>Start Date</th>
                            <th>Period</th>
                            <th>Expiry Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($funds as $fund)
                            <tr>
                                <td class="fw-bold text-dark">{{ $fund->fund_name }}</td>
                                <td>
                                    <span class="text-muted small fw-bold"><i class="far fa-calendar-alt me-2 text-primary"></i>{{ $fund->start_date }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small fw-bold">{{ $fund->period }}</span>
                                </td>
                                <td>
                                    @if ($fund->end_date)
                                        <span class="text-muted small fw-bold"><i class="far fa-calendar-check me-2 text-primary"></i>{{ $fund->end_date }}</span>
                                    @else
                                        <span class="text-muted small fw-bold">PRESENT</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="status-badge {{ strtolower($fund->status) === 'active' ? 'status-active' : 'status-expired' }}">
                                        {{ strtoupper($fund->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('fund.fund_update_info', [$fund->id]) }}"
                                            class="btn btn-icon btn-edit" title="Edit Fund">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('fund.show_cohort_related_fund', [$fund->id]) }}"
                                            class="btn btn-icon btn-show" title="Show Details">
                                            <i class="fas fa-eye"></i>
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
