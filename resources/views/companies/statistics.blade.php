@extends('layouts.app')

@section('page_title', 'Company Statistics')

@section('content')
    <style>
        /* --- BRANDED ODS & HYBRID DESIGN VARIABLES --- */
        :root {
            --ods-orange-100: #FF7900;
            --ods-orange-200: #F16E00;
            --ods-white:      #FFFFFF;
            --ods-black:      #000000;
            --ods-gray-100:   #FAFAFA;
            --ods-gray-200:   #F6F6F6;
            --ods-gray-300:   #EEEEEE;
            --ods-gray-400:   #DDDDDD;
            --ods-gray-500:   #CCCCCC;
            --ods-gray-600:   #999999;
            --ods-gray-700:   #666666;
            --ods-gray-900:   #333333;
            --ods-p-bg:       #F9FAFB;
            --ods-border:     var(--ods-gray-400);
            --ods-success:    #228722;
            --ods-danger:     #CD3C14;
            --blue-male:      #1e90ff;
            --pink-female:    #ff69b4;
        }

        .container-fluid-custom {
            padding: 1.5rem;
            background-color: var(--ods-p-bg);
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .page-header {
            margin-bottom: 2.5rem;
            border-left: 5px solid var(--ods-orange-100);
            padding-left: 1.5rem;
        }

        /* KPI Cards */
        .stat-card {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--ods-border);
            background: var(--ods-white);
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.12;
            position: absolute;
            right: 20px;
            bottom: 15px;
        }
        .text-orange {
            color: var(--ods-orange-100);
        }

        /* Charts & Distribution Cards */
        .chart-card {
            background: var(--ods-white);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--ods-border);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .chart-header {
            border-bottom: 1px solid var(--ods-gray-300);
            padding-bottom: 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-header h5 {
            font-weight: 700;
            margin: 0;
            color: var(--ods-black);
        }

        /* Lists */
        .distribution-item {
            margin-bottom: 1rem;
        }
        .distribution-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--ods-gray-700);
            margin-bottom: 4px;
        }
        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background-color: var(--ods-gray-300);
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        /* Gender Display */
        .gender-stat-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            border-radius: 8px;
            background-color: var(--ods-gray-200);
            margin-bottom: 10px;
        }
        .gender-stat-box i {
            font-size: 1.5rem;
        }

        /* Table Styling */
        .table-container {
            background: var(--ods-white);
            border-radius: 16px;
            border: 1px solid var(--ods-border);
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            margin-top: 1.5rem;
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
            border-bottom: 1px solid var(--ods-gray-300);
            vertical-align: middle;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover {
            background-color: var(--ods-gray-100);
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
        }
        .badge-active {
            background-color: rgba(34, 135, 34, 0.1);
            color: var(--ods-success);
        }
        .badge-inactive {
            background-color: rgba(205, 60, 20, 0.1);
            color: var(--ods-danger);
        }

        .badge-hires {
            background-color: rgba(255, 121, 0, 0.1);
            color: var(--ods-orange-100);
            font-weight: 700;
            font-size: 0.9rem;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .search-card {
            background: var(--ods-white);
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--ods-border);
            margin-bottom: 1.5rem;
        }

        .search-input {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--ods-border);
            padding: 0 0.75rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-weight: 500;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--ods-orange-100);
            box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
        }

        .filter-label {
            font-size: 0.75rem; 
            font-weight: 800;
            text-transform: uppercase;
            color: var(--ods-gray-700);
            margin-bottom: 2px;
            display: block;
            letter-spacing: 0.02em;
        }

        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="container-fluid container-fluid-custom fade-in">
        
        <!-- Page Header -->
        <header class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold m-0">Company Statistics</h2>
                <p class="text-muted small mb-0 mt-1 uppercase fw-bold" style="letter-spacing: 0.05em;">Performance analytics, academy pipelines & placement metrics</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('companies.index') }}" class="btn btn-outline-dark fw-bold">
                    <i class="fas fa-arrow-left me-2"></i>Back to Companies Insight
                </a>
                <button class="btn btn-dark fw-bold" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
            </div>
        </header>

        <!-- KPI Cards Grid -->
        <div class="row g-3 mb-4">
            <!-- Total Companies -->
            <div class="col-md-4 col-lg-2">
                <div class="stat-card p-3 d-flex flex-column h-100 justify-content-between">
                    <div>
                        <span class="filter-label text-muted">Total Companies</span>
                        <h3 class="fw-bold text-dark m-0 mt-1">{{ $totalCompaniesCount }}</h3>
                    </div>
                    <i class="bi bi-building stat-icon text-orange"></i>
                </div>
            </div>
            <!-- Total Hires -->
            <div class="col-md-4 col-lg-2">
                <div class="stat-card p-3 d-flex flex-column h-100 justify-content-between">
                    <div>
                        <span class="filter-label text-muted">Total Hires</span>
                        <h3 class="fw-bold text-dark m-0 mt-1">{{ $totalHires }}</h3>
                    </div>
                    <i class="bi bi-people-fill stat-icon text-orange"></i>
                </div>
            </div>
            <!-- Active Companies -->
            <div class="col-md-4 col-lg-2">
                <div class="stat-card p-3 d-flex flex-column h-100 justify-content-between">
                    <div>
                        <span class="filter-label text-muted">Active Companies</span>
                        <h3 class="fw-bold text-dark m-0 mt-1">{{ $activeCompaniesCount }}</h3>
                    </div>
                    <i class="bi bi-building-check stat-icon text-orange"></i>
                </div>
            </div>
            <!-- Average Hires per Company -->
            <div class="col-md-4 col-lg-2">
                <div class="stat-card p-3 d-flex flex-column h-100 justify-content-between">
                    <div>
                        <span class="filter-label text-muted">Avg Hires / Co</span>
                        <h3 class="fw-bold text-dark m-0 mt-1">{{ $averageHiresPerCompany }}</h3>
                    </div>
                    <i class="bi bi-calculator stat-icon text-orange"></i>
                </div>
            </div>
            <!-- First Hiring Date -->
            <div class="col-md-4 col-lg-2">
                <div class="stat-card p-3 d-flex flex-column h-100 justify-content-between">
                    <div>
                        <span class="filter-label text-muted">First Hiring Date</span>
                        <h5 class="fw-bold text-dark m-0 mt-1">
                            {{ $firstHiringDate ? \Carbon\Carbon::parse($firstHiringDate)->format('M d, Y') : 'N/A' }}
                        </h5>
                    </div>
                    <i class="bi bi-calendar-event stat-icon text-orange"></i>
                </div>
            </div>
            <!-- Latest Hiring Date -->
            <div class="col-md-4 col-lg-2">
                <div class="stat-card p-3 d-flex flex-column h-100 justify-content-between">
                    <div>
                        <span class="filter-label text-muted">Latest Hiring Date</span>
                        <h5 class="fw-bold text-dark m-0 mt-1">
                            {{ $latestHiringDate ? \Carbon\Carbon::parse($latestHiringDate)->format('M d, Y') : 'N/A' }}
                        </h5>
                    </div>
                    <i class="bi bi-calendar-check stat-icon text-orange"></i>
                </div>
            </div>
        </div>

        <!-- Advanced Filter Controls -->
        <div class="search-card">
            <form action="{{ route('companies.statistics') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-2 col-md-4">
                        <label class="filter-label">Company Name</label>
                        <select name="company" class="form-select search-input w-100">
                            <option value="">All Companies</option>
                            @foreach($companies as $c)
                                <option value="{{ $c->company_name }}" {{ $selectedCompany == $c->company_name ? 'selected' : '' }}>
                                    {{ $c->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="col-lg-2 col-md-4">
                        <label class="filter-label">Cohort / Group</label>
                        <select name="cohort_id" class="form-select search-input w-100">
                            <option value="">All Cohorts</option>
                            @foreach($cohorts as $coh)
                                <option value="{{ $coh->id }}" {{ $selectedCohort == $coh->id ? 'selected' : '' }}>
                                    {{ $coh->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> -->
                    <div class="col-lg-2 col-md-4">
                        <label class="filter-label">Employment Status</label>
                        <select name="status" class="form-select search-input w-100">
                            <option value="">All Statuses</option>
                            <option value="job offer" {{ $selectedStatus == 'job offer' ? 'selected' : '' }}>Job offer</option>
                            <option value="internship_for_employment" {{ ($selectedStatus == 'internship_for_employment' || $selectedStatus == 'internship_for_Employment' || $selectedStatus == 'internship for employment') ? 'selected' : '' }}>Internship for Employment</option>
                            <option value="freelance" {{ $selectedStatus == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="filter-label">From Date</label>
                        <input type="date" name="from_date" class="form-control search-input w-100" value="{{ $fromDate }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="filter-label">To Date</label>
                        <input type="date" name="to_date" class="form-control search-input w-100" value="{{ $toDate }}">
                    </div>
                    <div class="col-lg-2 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-dark fw-bold w-100" style="height: 42px; border-radius: 8px;">
                            Filter
                        </button>
                        <a href="{{ route('companies.statistics') }}" class="btn btn-outline-dark fw-bold w-100 d-flex align-items-center justify-content-center" style="height: 42px; border-radius: 8px;" title="Reset Filters">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Distributions / Statistics Breakdowns -->
        <div class="row g-4 mb-4">
            <!-- Top Hiring Companies -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="bi bi-trophy text-warning fs-5"></i>
                        <h5>Top Hiring Partners</h5>
                    </div>
                    <div class="chart-body">
                        @if($companyHires->count() > 0)
                            @foreach($companyHires->take(5) as $hire)
                                @php
                                    $percentage = $totalHiresCount > 0 ? round(($hire->trainees_count / $totalHiresCount) * 100) : 0;
                                @endphp
                                <div class="distribution-item">
                                    <div class="distribution-label">
                                        <span>{{ $hire->company_name }}</span>
                                        <span>{{ $hire->trainees_count }} Hires ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-bar-fill" style="width: {{ $percentage }}%; background-color: var(--ods-orange-100);"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fa-2x mb-2"></i>
                                <p class="small">No placement data available yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Placements by Academy -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="bi bi-mortarboard-fill text-orange fs-5"></i>
                        <h5>Placements by Academy</h5>
                    </div>
                    <div class="chart-body">
                        @if($academyHires->count() > 0)
                            @php
                                $maxAcademyHires = $academyHires->max('hires_count') ?: 1;
                            @endphp
                            @foreach($academyHires as $ah)
                                @php
                                    $barPercent = round(($ah->hires_count / $maxAcademyHires) * 100);
                                @endphp
                                <div class="distribution-item">
                                    <div class="distribution-label">
                                        <span>{{ $ah->academy_name }} Academy</span>
                                        <span>{{ $ah->hires_count }} placed</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-bar-fill" style="width: {{ $barPercent }}%; background-color: var(--ods-black);"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fa-2x mb-2"></i>
                                <p class="small">No academy pipeline data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Diversity / Gender Placements Split -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="bi bi-pie-chart text-primary fs-5"></i>
                        <h5>Hired Trainees Gender Split</h5>
                    </div>
                    <div class="chart-body d-flex flex-column justify-content-center h-75">
                        @php
                            $maleCount = 0;
                            $femaleCount = 0;
                            foreach ($genderHires as $gh) {
                                if (strtolower($gh->gender) === 'male') {
                                    $maleCount = $gh->hires_count;
                                } elseif (strtolower($gh->gender) === 'female') {
                                    $femaleCount = $gh->hires_count;
                                }
                            }
                            $genderTotal = $maleCount + $femaleCount;
                            $malePercent = $genderTotal > 0 ? round(($maleCount / $genderTotal) * 100) : 0;
                            $femalePercent = $genderTotal > 0 ? round(($femaleCount / $genderTotal) * 100) : 0;
                        @endphp

                        <div class="gender-stat-box border-start border-4 border-info">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-gender-male text-info"></i>
                                <div>
                                    <span class="d-block fw-bold text-dark">Male Graduates</span>
                                    <small class="text-muted">Total Placed Hires</small>
                                </div>
                            </div>
                            <span class="fs-4 fw-bold text-dark">{{ $maleCount }} <small class="fs-6 text-muted">({{ $malePercent }}%)</small></span>
                        </div>

                        <div class="gender-stat-box border-start border-4" style="border-left-color: var(--pink-female) !important;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-gender-female" style="color: var(--pink-female);"></i>
                                <div>
                                    <span class="d-block fw-bold text-dark">Female Graduates</span>
                                    <small class="text-muted">Total Placed Hires</small>
                                </div>
                            </div>
                            <span class="fs-4 fw-bold text-dark">{{ $femaleCount }} <small class="fs-6 text-muted">({{ $femalePercent }}%)</small></span>
                        </div>

                        @if($genderTotal > 0)
                            <div class="progress-bar-custom mt-3" style="height: 12px;">
                                <div class="d-flex h-100">
                                    <div class="h-100" style="width: {{ $malePercent }}%; background-color: var(--blue-male);" title="Male: {{ $malePercent }}%"></div>
                                    <div class="h-100" style="width: {{ $femalePercent }}%; background-color: var(--pink-female);" title="Female: {{ $femalePercent }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- New Visual Analytics Section -->
        <h4 class="fw-bold mt-5 mb-4 text-dark"><i class="bi bi-bar-chart-line text-orange me-2"></i>Visual Analytics Dashboard</h4>

        <!-- Row 1: Hiring Trends & Hires Per Cohort by Company -->
        <div class="row g-4 mb-4">
            <!-- Hiring Trends Over Time -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-graph-up text-orange fs-5"></i>
                            <h5 class="m-0">Hiring Trends Over Time</h5>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-dark active" id="btnMonthlyTrend">Monthly</button>
                            <button type="button" class="btn btn-outline-dark" id="btnYearlyTrend">Yearly</button>
                        </div>
                    </div>
                    <div class="chart-body" style="position: relative; height: 350px;">
                        <canvas id="hiringTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Hires per Cohort by Academy/Cohort -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-grid-fill text-orange fs-5"></i>
                            <h5 class="m-0">Hires per Cohort</h5>
                        </div>
                        <div class="d-flex gap-2">
                            <select id="academyFilter" class="form-select form-select-sm" style="width: 140px;">
                                <option value="">All Academies</option>
                            </select>
                            <select id="cohortFilter" class="form-select form-select-sm" style="width: 100px;">
                                <option value="">All Cohorts</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-body" style="position: relative; height: 350px;">
                        <canvas id="cohortHiresChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Student Distribution, Company Activity Status & Cohort Engagement -->
        <div class="row g-4 mb-4">
            <!-- Student Distribution by Company -->
            <div class="col-lg-5">
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="bi bi-funnel text-orange fs-5"></i>
                        <h5 class="m-0">Student Distribution by Company</h5>
                    </div>
                    <div class="chart-body" style="position: relative; height: 350px;">
                        <canvas id="studentDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Company Activity Status -->
            <div class="col-lg-3">
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="bi bi-activity text-orange fs-5"></i>
                        <h5 class="m-0">Company Deal Status</h5>
                    </div>
                    <div class="chart-body d-flex flex-column justify-content-center align-items-center" style="height: 350px;">
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="companyActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Companies by Cohort Engagement -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="bi bi-star-fill text-warning fs-5"></i>
                        <h5 class="m-0">Top Engaged Companies</h5>
                    </div>
                    <div class="chart-body" style="position: relative; height: 350px;">
                        <canvas id="cohortEngagementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Controls & Search -->
        <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
            <h5 class="fw-bold m-0 text-dark"><i class="fas fa-list-alt me-2 text-orange"></i>Hiring Insights Details</h5>
            <div style="width: 320px;">
                <input type="text" id="custom-search" class="form-control search-input w-100" placeholder="Real-time details search...">
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table id="companies-statistics-table" class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th class="text-center">Deal Status</th>
                            <th class="text-center">Employers Contacts</th>
                            <th class="text-center">Placed Hires</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companiesList as $company)
                            <tr>
                                <td class="fw-bold text-dark">{{ $company->company_name }}</td>
                                <td>{{ $company->company_email ?: 'N/A' }}</td>
                                <td class="text-center" data-filter="{{ $company->type_of_deal == 1 ? 'Active' : 'Inactive' }}">
                                    @if($company->type_of_deal == 1)
                                        <span class="badge-status badge-active">Active</span>
                                    @else
                                        <span class="badge-status badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $company->employers_count }}</td>
                                <td class="text-center">
                                    <span class="badge-hires">{{ $company->trainees_hired }}</span>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#companies-statistics-table').DataTable({
                dom: 't<"d-flex justify-content-between p-3"ip>',
                lengthChange: false,
                ordering: true,
                order: [[4, 'desc']], // Order by Hires count by default
                columnDefs: [
                    { targets: [2, 3, 4], searchable: true }
                ]
            });

            // Handle Custom Search
            $('#custom-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Handle Status filter
            $('#status-filter').on('change', function() {
                var val = $(this).val();
                if (val === "") {
                    table.column(2).search('').draw();
                } else {
                    table.column(2).search('^' + val + '$', true, false).draw();
                }
            });

            // --- CHART.JS ANALYTICS DASHBOARD INITIALIZATIONS ---

            // 1. Hiring Trends Data
            const monthlyLabels = {!! json_encode($monthlyTrends->pluck('formatted_month')) !!};
            const monthlyData = {!! json_encode($monthlyTrends->pluck('count')) !!};
            const yearlyLabels = {!! json_encode($yearlyTrends->pluck('formatted_year')) !!};
            const yearlyData = {!! json_encode($yearlyTrends->pluck('count')) !!};

            const trendCtx = document.getElementById('hiringTrendsChart').getContext('2d');
            let trendsChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Trainees Hired',
                        data: monthlyData,
                        borderColor: '#FF7900',
                        backgroundColor: 'rgba(255, 121, 0, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });

            $('#btnMonthlyTrend').on('click', function() {
                $(this).addClass('active').addClass('btn-dark').removeClass('btn-outline-dark');
                $('#btnYearlyTrend').removeClass('active').addClass('btn-outline-dark').removeClass('btn-dark');
                trendsChart.data.labels = monthlyLabels;
                trendsChart.data.datasets[0].data = monthlyData;
                trendsChart.update();
            });

            $('#btnYearlyTrend').on('click', function() {
                $(this).addClass('active').addClass('btn-dark').removeClass('btn-outline-dark');
                $('#btnMonthlyTrend').removeClass('active').addClass('btn-outline-dark').removeClass('btn-dark');
                trendsChart.data.labels = yearlyLabels;
                trendsChart.data.datasets[0].data = yearlyData;
                trendsChart.update();
            });

            // 2. Hires per Cohort Data
            const cohortHiresData = {!! json_encode($cohortCompanyHires) !!};
            const cohortCtx = document.getElementById('cohortHiresChart').getContext('2d');
            let cohortHiresChart;

            function buildFilters() {
                const academies = new Set();
                const cohorts = new Set();
                cohortHiresData.forEach(item => {
                    academies.add(item.academy_name);
                    cohorts.add(item.cohort_name);
                });
                Array.from(academies).sort().forEach(a => {
                    $('#academyFilter').append('<option value="' + a + '">' + a + '</option>');
                });
                Array.from(cohorts).sort().forEach(c => {
                    $('#cohortFilter').append('<option value="' + c + '">' + c + '</option>');
                });
            }

            function updateCohortChart() {
                const selectedAcademy = $('#academyFilter').val();
                const selectedCohort = $('#cohortFilter').val();

                let filtered = cohortHiresData;
                if (selectedAcademy) {
                    filtered = filtered.filter(item => item.academy_name === selectedAcademy);
                }
                if (selectedCohort) {
                    filtered = filtered.filter(item => item.cohort_name === selectedCohort);
                }

                if (!selectedAcademy && !selectedCohort) {
                    let cohortCounts = {};
                    filtered.forEach(item => {
                        const label = item.academy_name + ' ' + item.cohort_name;
                        cohortCounts[label] = (cohortCounts[label] || 0) + parseInt(item.hires_count);
                    });
                    const labels = Object.keys(cohortCounts);
                    const values = Object.values(cohortCounts);

                    if (cohortHiresChart) cohortHiresChart.destroy();
                    cohortHiresChart = new Chart(cohortCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Hires',
                                data: values,
                                backgroundColor: '#000',
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                        }
                    });
                } else {
                    const companyCounts = {};
                    filtered.forEach(item => {
                        companyCounts[item.company_name] = (companyCounts[item.company_name] || 0) + parseInt(item.hires_count);
                    });
                    const sorted = Object.entries(companyCounts).sort((a, b) => b[1] - a[1]);
                    const labels = sorted.map(e => e[0]);
                    const values = sorted.map(e => e[1]);

                    let label = '';
                    if (selectedAcademy && selectedCohort) label = selectedAcademy + ' ' + selectedCohort;
                    else if (selectedAcademy) label = selectedAcademy;
                    else label = selectedCohort;

                    if (cohortHiresChart) cohortHiresChart.destroy();
                    cohortHiresChart = new Chart(cohortCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Hires' + (label ? ' (' + label + ')' : ''),
                                data: values,
                                backgroundColor: '#FF7900',
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                        }
                    });
                }
            }

            buildFilters();
            updateCohortChart();

            $('#academyFilter, #cohortFilter').on('change', function() {
                updateCohortChart();
            });

            // 3. Student Distribution by Company Chart
            const companyNames = {!! json_encode($companyHires->take(10)->pluck('company_name')) !!};
            const traineesHiredCounts = {!! json_encode($companyHires->take(10)->pluck('trainees_count')) !!};

            const distCtx = document.getElementById('studentDistributionChart').getContext('2d');
            new Chart(distCtx, {
                type: 'bar',
                data: {
                    labels: companyNames,
                    datasets: [{
                        label: 'Total Hires',
                        data: traineesHiredCounts,
                        backgroundColor: 'rgba(255, 121, 0, 0.85)',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });

            // 4. Company Activity Deal Status Chart
            const activeCount = {{ $activeCompaniesCount }};
            const inactiveCount = {{ $inactiveCompaniesCount }};

            const actCtx = document.getElementById('companyActivityChart').getContext('2d');
            new Chart(actCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Active Partners', 'Inactive Partners'],
                    datasets: [{
                        data: [activeCount, inactiveCount],
                        backgroundColor: ['#228722', '#CD3C14'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '65%'
                }
            });

            // 5. Cohort Engagement Chart
            const engagementLabels = {!! json_encode($companyCohortEngagement->pluck('company_name')) !!};
            const engagementCohorts = {!! json_encode($companyCohortEngagement->pluck('cohorts_count')) !!};
            const engagementTotalHires = {!! json_encode($companyCohortEngagement->pluck('total_hires')) !!};

            const engCtx = document.getElementById('cohortEngagementChart').getContext('2d');
            new Chart(engCtx, {
                type: 'bar',
                data: {
                    labels: engagementLabels,
                    datasets: [
                        {
                            label: 'Unique Cohorts Hired From',
                            data: engagementCohorts,
                            backgroundColor: '#FF7900',
                            borderRadius: 4
                        },
                        {
                            label: 'Total Hires',
                            data: engagementTotalHires,
                            backgroundColor: '#000',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        });
    </script>
@endsection
