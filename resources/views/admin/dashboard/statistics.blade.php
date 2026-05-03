@extends('layouts.app')

@section('page_title', 'Performance Analytics & Statistics')

@section('content')
<style>
    :root {
        --orange-primary: #f16e00;
        --orange-hover: #ff7900;
        --blue-male: #1e90ff;
        --pink-female: #ff69b4;
        --bg-light: #f8f9fa;
    }

    /* Filters */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    /* Summary Cards */
    .stat-card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.15;
        position: absolute;
        right: 15px;
        bottom: 10px;
    }
    
    /* Global Styles */
    .section-title {
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title i { color: var(--orange-primary); }

    /* Table Styling */
    .table-container {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 40px;
    }
    .table-statistics {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-statistics thead th {
        background-color: var(--orange-primary);
        color: white;
        padding: 15px 10px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border: none;
        text-align: center;
    }
    .table-statistics thead th:first-child { border-radius: 10px 0 0 0; text-align: left; }
    .table-statistics thead th:last-child { border-radius: 0 10px 0 0; }
    
    .table-statistics tbody td {
        padding: 15px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        text-align: center;
    }
    .table-statistics tbody td:first-child { text-align: left; }
    .table-statistics tbody tr:hover { background-color: rgba(241, 110, 0, 0.02); }
    
    /* KPI Indicators */
    .kpi-total { font-weight: 700; font-size: 1.05rem; display: block; }
    .gender-split { display: flex; justify-content: center; gap: 10px; margin-top: 4px; }
    .gender-item { font-size: 0.75rem; display: flex; align-items: center; gap: 3px; }
    .gender-icon-male { color: var(--blue-male); }
    .gender-icon-female { color: var(--pink-female); }
    
    .rate-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
        background: rgba(241, 110, 0, 0.1);
        color: var(--orange-primary);
    }
    .academy-name { font-weight: 600; color: #333; font-size: 0.95rem; }

    /* Hiring List */
    .hiring-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        border-radius: 10px;
        background: var(--bg-light);
        margin-bottom: 8px;
        transition: background 0.2s;
    }
    .hiring-item:hover { background: #eee; }
    .hiring-name { font-weight: 600; font-size: 0.9rem; }
    .hiring-count { font-weight: 800; color: var(--orange-primary); font-size: 1.1rem; }

    .fade-in { animation: fadeIn 0.5s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container-fluid py-4 fade-in">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Advanced Performance Data</h1>
            <p class="text-muted small mb-0">Filtered insights into recruitment, graduation, and employment KPIs.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-dark btn-sm rounded-pill px-4" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form action="{{ route('statistics.dashboard') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase">Donor / Fund</label>
                <select name="donor_id" class="form-select border-0 bg-light shadow-none">
                    <option value="">All Donors</option>
                    @foreach($donors as $donor)
                        <option value="{{ $donor->id }}" {{ $selectedDonor == $donor->id ? 'selected' : '' }}>{{ $donor->fund_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase">Location / Academy</label>
                <select name="academy_id" class="form-select border-0 bg-light shadow-none">
                    <option value="">All Academies</option>
                    @foreach($academies as $academy)
                        <option value="{{ $academy->id }}" {{ $selectedAcademy == $academy->id ? 'selected' : '' }}>{{ $academy->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-uppercase">Year</label>
                <select name="year" class="form-select border-0 bg-light shadow-none">
                    <option value="">Any</option>
                    @foreach($years as $yearOpt)
                        <option value="{{ $yearOpt }}" {{ isset($selectedYear) && $selectedYear == $yearOpt ? 'selected' : '' }}>{{ $yearOpt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-uppercase">Filter By</label>
                <select name="year_type" class="form-select border-0 bg-light shadow-none">
                    <option value="start" {{ isset($selectedYearType) && $selectedYearType == 'start' ? 'selected' : '' }}>Start Year</option>
                    <option value="end" {{ isset($selectedYearType) && $selectedYearType == 'end' ? 'selected' : '' }}>End Year</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 shadow-sm" style="background: var(--orange-primary); border: none;">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    @if($global['trainees'] > 0)
    <!-- Summary Cards -->
    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card stat-card bg-white h-100 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="text-secondary text-uppercase mb-2 small fw-bold">Active Trainees</h6>
                    <h2 class="fw-bold mb-0">{{ $global['trainees'] }}</h2>
                    <div class="mt-2 small text-muted">
                        <span class="me-2"><i class="fas fa-male gender-icon-male"></i> {{ $global['male'] }}</span>
                        <span><i class="fas fa-female gender-icon-female"></i> {{ $global['female'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-white h-100 shadow-sm border-start border-4 border-warning" style="border-left-color: var(--orange-primary) !important;">
                <div class="card-body p-4">
                    <h6 class="text-secondary text-uppercase mb-2 small fw-bold">Graduation Count</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ $global['graduates'] }}</h2>
                    <div class="mt-2 small">
                        <span class="badge bg-light text-dark rounded-pill">{{ $global['trainees'] > 0 ? round(($global['graduates']/$global['trainees'])*100) : 0 }}% Compliance</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-dark text-white h-100 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="text-light text-uppercase mb-2 small fw-bold" style="opacity: 0.7;">Employment Success</h6>
                    <h2 class="fw-bold mb-0">{{ $global['employed'] }}</h2>
                    <div class="mt-2 small text-warning fw-bold">{{ $global['employment_rate'] }}% Overall Success</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-white h-100 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="text-secondary text-uppercase mb-2 small fw-bold">Available Talent</h6>
                    <h2 class="fw-bold mb-0">{{ $global['available'] }}</h2>
                    <div class="mt-2 small text-muted">Awaiting Placement</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tables on Left -->
        <div class="col-lg-9">
            <!-- Academy Performance Table -->
            <div class="table-container">
                <h5 class="section-title"><i class="fas fa-university"></i> Performance per Academy</h5>
                <div class="table-responsive">
                    <table class="table table-statistics">
                        <thead>
                            <tr>
                                <th class="text-start">Academy</th>
                                <th>Total Trainees</th>
                                <th>Graduates</th>
                                <th>Available</th>
                                <th>Employed</th>
                                <th>Employ. Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employmentData as $data)
                            <tr>
                                <td class="text-start academy-name">{{ $data['academy'] }}</td>
                                <td>
                                    <span class="kpi-total">{{ $data['trainees']['total'] }}</span>
                                    <div class="gender-split">
                                        <div class="gender-item"><i class="fas fa-male gender-icon-male"></i> {{ $data['trainees']['male'] }}</div>
                                        <div class="gender-item"><i class="fas fa-female gender-icon-female"></i> {{ $data['trainees']['female'] }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="kpi-total">{{ $data['graduates']['total'] }}</span>
                                    <div class="gender-split">
                                        <div class="gender-item"><i class="fas fa-male gender-icon-male"></i> {{ $data['graduates']['male'] }}</div>
                                        <div class="gender-item"><i class="fas fa-female gender-icon-female"></i> {{ $data['graduates']['female'] }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="kpi-total text-secondary">{{ $data['available']['total'] }}</span>
                                    <div class="gender-split">
                                        <div class="gender-item"><i class="fas fa-male gender-icon-male"></i> {{ $data['available']['male'] }}</div>
                                        <div class="gender-item"><i class="fas fa-female gender-icon-female"></i> {{ $data['available']['female'] }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="kpi-total">{{ $data['employed']['total'] }}</span>
                                    <div class="gender-split">
                                        <div class="gender-item"><i class="fas fa-male gender-icon-male"></i> {{ $data['employed']['male'] }}</div>
                                        <div class="gender-item"><i class="fas fa-female gender-icon-female"></i> {{ $data['employed']['female'] }}</div>
                                    </div>
                                </td>
                                <td><span class="rate-badge">{{ $data['employed']['overall_rate'] }}%</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Yearly Performance Table -->
            <div class="table-container">
                <h5 class="section-title"><i class="fas fa-calendar-alt"></i> Performance per Year</h5>
                <div class="table-responsive">
                    <table class="table table-statistics">
                        <thead>
                            <tr>
                                <th class="text-start">Year</th>
                                <th>Total Trainees</th>
                                <th>Graduates</th>
                                <th>Available</th>
                                <th>Employed</th>
                                <th>Employ. Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($yearlyData as $data)
                            <tr>
                                <td class="text-start fw-bold">{{ $data['year'] }}</td>
                                <td>
                                    <span class="kpi-total">{{ $data['trainees']['total'] }}</span>
                                    <div class="gender-split">
                                        <span class="gender-item"><i class="fas fa-male gender-icon-male"></i> {{ $data['trainees']['male'] }}</span>
                                        <span class="gender-item"><i class="fas fa-female gender-icon-female"></i> {{ $data['trainees']['female'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="kpi-total">{{ $data['graduates']['total'] }}</span>
                                    <div class="gender-split">
                                        <span class="gender-item"><i class="fas fa-male gender-icon-male"></i> {{ $data['graduates']['male'] }}</span>
                                        <span class="gender-item"><i class="fas fa-female gender-icon-female"></i> {{ $data['graduates']['female'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="kpi-total text-secondary">{{ $data['available']['total'] }}</span>
                                </td>
                                <td>
                                    <span class="kpi-total">{{ $data['employed']['total'] }}</span>
                                    <div class="gender-split">
                                        <span class="gender-item"><i class="fas fa-male gender-icon-male"></i> {{ $data['employed']['male'] }}</span>
                                        <span class="gender-item"><i class="fas fa-female gender-icon-female"></i> {{ $data['employed']['female'] }}</span>
                                    </div>
                                </td>
                                <td><span class="rate-badge">{{ $data['employment_rate'] }}%</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions on Right -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-briefcase text-primary"></i> Top Hiring Companies
                    </h5>
                    <p class="text-muted small mb-4">Cumulative student hires by recruiters.</p>
                    
                    @if($companyHires->count() > 0)
                        @foreach($companyHires as $hire)
                            <div class="hiring-item">
                                <span class="hiring-name">{{ $hire->company_name }}</span>
                                <span class="hiring-count">{{ $hire->graduates_hired }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-building fa-2x text-light mb-2"></i>
                            <p class="text-muted small">No hiring data for selected filters.</p>
                        </div>
                    @endif
                </div>
            </div>
            
           
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-chart-area fa-3x text-muted mb-3"></i>
        <h3>No Data Found</h3>
        <p class="text-muted">Adjust your filters to see metrics for different academies or donors.</p>
        <a href="{{ route('statistics.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">Clear All Filters</a>
    </div>
    @endif
</div>
@endsection
