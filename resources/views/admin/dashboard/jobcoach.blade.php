@extends('layouts.app')

@section('page_title', 'Job Coach Dashboard')

@section('content')
<style>

/* Table base style - Unifying with Orange Design System */
.table-custom {
    width: 100%;
    border-collapse: collapse;
    background-color: var(--bs-gray-100);
}
.card_title_style{
    font-size: 17px;
    color:#f16e00;
}
.table-custom th,
.table-custom td {
    border: 1px solid var(--bs-gray-300);
    padding: 12px;
    text-align: center;
}

.table-custom thead {
    background-color: var(--bs-primary);
    color: var(--bs-white);
}

.odd-row {
    background-color: var(--bs-gray-200);
}

.even-row {
    background-color: var(--bs-white);
}

.table-main-row:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
}

.sub-row-card {
    padding: 12px;
    border-radius: var(--bs-border-radius);
    font-size: 0.85rem;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 6px;
}

/* Updated Border Style for Fund Cards */
.fund-card-header {
    border: 2px solid var(--bs-secondary) !important;
    background-color: var(--bs-white);
    color: var(--bs-secondary);
    font-weight: 600;
    border-radius: var(--bs-border-radius) var(--bs-border-radius) 0 0;
}

.fund-card-body {
    border-left: 2px solid var(--bs-secondary);
    border-right: 2px solid var(--bs-secondary);
    border-bottom: 2px solid var(--bs-secondary);
    background-color: var(--bs-tertiary-bg);
}

.fund-view-btn {
    border: 1px solid var(--bs-secondary) !important;
}

.fund-card-wrapper {
    border: none !important;
    border-radius: var(--bs-border-radius);
    overflow: hidden;
}
</style>

    <div class="row first_section">
        <!-- Employment Rate Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-bg-dark mb-3 border border-white h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                                class="solaris-icon si-medal" viewBox="0 0 1000 1000">
                                <path
                                    d="M667.678 357.322 925 100H625L517.678 207.322ZM500 425a200 200 0 1 1-141.421 58.579A198.7 198.7 0 0 1 500 425m0-75c-151.878 0-275 123.122-275 275s123.122 275 275 275 275-123.122 275-275-123.122-275-275-275M350 576.412 429.412 647l-26.471 105.882L500 699.941l97.059 52.941L570.588 647 650 576.412l-105.882-17.647L500 467l-44.118 91.765ZM500 325a298.8 298.8 0 0 1 129.164 29.164L375 100H75l268.819 268.819A298.6 298.6 0 0 1 500 325"
                                    style="fill-rule:evenodd" />
                            </svg>
                        </div>
                        <div class="col-8 d-flex flex-column justify-content-center ps-3">
                            <h6 class="mb-1 card_title_style">Employment Rate</h6>
                            <p class="card-text fs-4 mb-0 fw-bold">{{$overallEmploymentRate}} %</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Available Trainees Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-bg-dark mb-3 border border-white h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                                class="solaris-icon si-group" viewBox="0 0 1000 1000">
                                <path
                                    d="M338 875V696.837A100.18 100.18 0 0 1 263 600V487.5a187.2 187.2 0 0 1 17.626-79.433 166.44 166.44 0 0 1-107.2-45.325A152.94 152.94 0 0 0 88 500v119.118C88 658.1 120.015 690 159 690v185a49.65 49.65 0 0 0 49.588 50h148.57A74.7 74.7 0 0 1 338 875m489.576-512.258a166.44 166.44 0 0 1-107.2 45.325A187.2 187.2 0 0 1 738 487.5V600a100.18 100.18 0 0 1-75 96.837V875a74.7 74.7 0 0 1-19.158 50h148.57A49.65 49.65 0 0 0 842 875V690c38.985 0 71-31.9 71-70.882V500a152.94 152.94 0 0 0-85.424-137.258M713 125a124.4 124.4 0 0 0-65.376 18.446c.9 1.913 1.769 3.84 2.6 5.794a162.38 162.38 0 0 1-24.162 166.435l7.231 3.564a187.9 187.9 0 0 1 66.676 55.086A125 125 0 1 0 713 125m-75 87.5A137.5 137.5 0 1 1 500.5 75 137.5 137.5 0 0 1 638 212.5m-15.763 129.164a177.47 177.47 0 0 1-243.474 0A162.5 162.5 0 0 0 288 487.5V600a75 75 0 0 0 75 75v200a50 50 0 0 0 50 50h175a50 50 0 0 0 50-50V675a75 75 0 0 0 75-75V487.5a162.5 162.5 0 0 0-90.763-145.836m-321.2 32.661a187.9 187.9 0 0 1 66.676-55.086l7.231-3.564A162.7 162.7 0 0 1 350.78 149.24c.827-1.954 1.7-3.881 2.6-5.794A125 125 0 1 0 288 375a126 126 0 0 0 13.035-.675Z"
                                    style="fill-rule:evenodd" />
                            </svg>
                        </div>
                        <div class="col-8 d-flex flex-column justify-content-center ps-3">
                            <h6 class="card_title_style mb-1 ">Total Trainees</h6>
                            <p class="card-text fs-4 mb-0 fw-bold">{{$overall_academy_Trainee}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Trainees Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-bg-dark mb-3 border border-white h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                                class="solaris-icon si-training-session" viewBox="0 0 1000 1000">
                                <path
                                    d="M848.963 713H150.038a25 25 0 0 0-22.971 34.848L175 863h24.991v75H800v-75h24.771l47.162-115.152A25 25 0 0 0 848.963 713m-228.38-271.592L499.887 538l-120.7-96.592A162.54 162.54 0 0 0 249.975 600.5V688H475v-32a49.869 49.869 0 1 1 50 0v32h225v-88c0-78.354-55.64-143.25-129.417-158.592M650 212l-141.858 49.6-8.255 2.89-8.256-2.89L350 212v101a150 150 0 0 0 300 0zm60-48 39.8-13.5L499.887 63l-249.912 87.5L499.887 238 690 171v167l-10.177 50H721l-11-50z"
                                    style="fill-rule:evenodd" />
                            </svg>
                        </div>
                        <div class="col-8 d-flex flex-column justify-content-center ps-3">
                            <h6 class="mb-1 card_title_style">Available Trainees</h6>
                            <p class="card-text fs-4 mb-0 fw-bold">{{$totalAvailable}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-bg-dark mb-3 border border-white h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                                class="solaris-icon si-desk" viewBox="0 0 1000 1000">
                                <path
                                    d="M500 150H175m700 325H450v-15c0-5.523-5.6-10-12.5-10H375v-25h-75v25h-62.5c-6.9 0-12.5 4.477-12.5 10v15H125a25.073 25.073 0 0 0-25 25v25a25 25 0 0 0 25 25h25v325h50V582.191A32.19 32.19 0 0 1 232.191 550h210.618A32.19 32.19 0 0 1 475 582.191V875h375V550h25a25 25 0 0 0 25-25v-25a25.073 25.073 0 0 0-25-25m-75 307.292A17.71 17.71 0 0 1 782.292 800H542.708A17.71 17.71 0 0 1 525 782.292v-64.584A17.71 17.71 0 0 1 542.708 700h74.957a17.7 17.7 0 0 1 12.522 5.187l14.626 14.626A17.7 17.7 0 0 0 657.335 725h10.33a17.7 17.7 0 0 0 12.522-5.187l14.626-14.626A17.7 17.7 0 0 1 707.335 700h74.957A17.71 17.71 0 0 1 800 717.708zm0-125A17.71 17.71 0 0 1 782.292 675H542.708A17.71 17.71 0 0 1 525 657.292v-64.584A17.71 17.71 0 0 1 542.708 575h74.957a17.7 17.7 0 0 1 12.522 5.187l14.626 14.626A17.7 17.7 0 0 0 657.335 600h10.33a17.7 17.7 0 0 0 12.522-5.187l14.626-14.626A17.7 17.7 0 0 1 707.335 575h74.957A17.71 17.71 0 0 1 800 592.708zM173.438 400h328.124A23.68 23.68 0 0 0 525 376.087V148.913A23.68 23.68 0 0 0 501.562 125H173.438A23.68 23.68 0 0 0 150 148.913v227.174A23.68 23.68 0 0 0 173.438 400M175 150h325v200H175z"
                                    style="fill-rule:evenodd" />
                            </svg>
                        </div>
                        <div class="col-8 d-flex flex-column justify-content-center ps-3">
                            <h6 class="mb-1 card_title_style">Total Companies</h6>
                            <p class="card-text fs-4 mb-0 fw-bold">{{$totalCompanies}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row ">
        <div class="card mb-4 border-0">
            <div class="card-body ">
                <div class="row">
                    <div class="col-md-12 py-lg-5">
                        <div class="d-flex mb-3">
                            <h2 class="mb-0">Academy Employment Rates</h2>
                        </div>
                        <div id="plotly-chart"></div>

                        <!-- table section -->
                        <table class="table table-custom table-hover align-middle shadow-sm">
                            <thead>
                                <tr>
                                    <th>Academy</th>
                                    <th>Total Trainees</th>
                                    <th>Total Graduates</th>
                                    <th>Total Available</th>
                                    <th>Employed Trainees</th>
                                    <th>Employment Rate</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employmentData as $rate)
                                <tr class="table-main-row @if ($loop->even) even-row @else odd-row @endif">
                                    <td><strong>{{ $rate['academy'] }}</strong></td>
                                    <td>{{ $rate['totalTrainees'] }}</td>
                                    <td>{{ $rate['totalGraduates'] }}</td>
                                    <td>{{ $rate['availableTrainees'] }}</td>
                                    <td>{{ $rate['employedTrainees'] }}</td>
                                    <td>{{ $rate['employmentRate'] }} %</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm"
                                            data-bs-toggle="collapse" data-bs-target="#details-{{ $loop->index }}">
                                            <i class="fas fa-chevron-down"></i> View
                                        </button>
                                    </td>
                                </tr>
                                <tr id="details-{{ $loop->index }}" class="collapse">
                                    <td colspan="7">
                                        <div class="row g-2 text-center">
                                            @php
                                            $categories = [
                                            'Total Trainees' => 'totalTrainees',
                                            'Graduates' => 'totalGraduates',
                                            'Available' => 'availableTrainees',
                                            'Employed' => 'employedTrainees',
                                            'Employment Rate' => 'employmentRate' // only here we show male/female rate
                                            ];
                                            $bgColors = ['#ffe6e6', '#e6f7ff', '#fff5e6', '#f0e6ff', '#e6ffe6'];
                                            @endphp

                                            @foreach ($categories as $index => $key)
                                            <div class="col">
                                                <div class="sub-row-card p-3 rounded"
                                                    style="background-color: {{ $bgColors[$index] ?? '#f0f0f0' }}">
                                                    <p class="mb-1 fw-semibold" style="font-size: 1.1rem;">{{ $index }}
                                                    </p>
                                                    <p class="mb-0" style="font-size: 1.10rem;">
                                                        @if ($key !== 'employmentRate')
                                                        <i class="fas fa-male" style="color: #1e90ff;"></i>
                                                        {{ $rate['maleFemaleStats'][$key]['male'] ?? 0 }}
                                                        |
                                                        <i class="fas fa-female" style="color: #ff69b4;"></i>
                                                        {{ $rate['maleFemaleStats'][$key]['female'] ?? 0 }}
                                                        @else
                                                        <!-- Show male/female employment rate -->
                                                        <i class="fas fa-male" style="color: #1e90ff;"></i>
                                                        {{ $rate['maleEmploymentRate'] ?? 0 }} %
                                                        |
                                                        <i class="fas fa-female" style="color: #ff69b4;"></i>
                                                        {{ $rate['femaleEmploymentRate'] ?? 0 }} %
                                                        @endif
                                                    </p>
                                                </div>

                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>



                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="col-12">
                        <canvas id="academyEmploymentChart" width="1200" height="400"></canvas>
                    </div>
                </div>

            </div>

        </div>

    </div>



    <div class="row">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="mb-4">Employment Rate per Fund</h2>

                @foreach ($fundData as $data)
                    <div class="card mb-4 shadow-sm rounded-4 overflow-hidden fund-card-wrapper">

                    <!-- Header -->
                        <div class="card-header d-flex justify-content-between align-items-center px-3 py-2 fund-card-header">


                        @php
                            $fund = strtolower($data['fund_name']);
                            $logoPath = null;

                            if ($fund == 'orange') {
                                $logoPath = asset('assets/images/Orange_logo.png');
                            } elseif ($fund == 'eu') {
                                $logoPath = asset('assets/images/eu.svg.png');
                            } elseif ($fund == 'proparco') {
                                $logoPath = asset('assets/images/proparco.jpg');
                            } elseif ($fund == 'digiskills rfa2') {
                                $logoPath = asset('assets/images/digiskills.png');
                            }
                        @endphp

                        <div style="display: flex; align-items: center; gap: 6px;">
                            @if($logoPath)
                                <img src="{{ $logoPath }}" width="60" height="60" alt="{{ $data['fund_name'] }} Logo">
                            @endif

                            <span style="font-weight: 800; font-size: 20px; margin-left: 10%">{{ $data['fund_name'] }}</span>
                        </div>


                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm fund-view-btn"  data-bs-toggle="collapse" data-bs-target="#fund-{{ $loop->index }}">
                            {{--view fund --}}
                            <i class="fas fa-chevron-down"></i> View
                        </button>
                    </div>

                    <!-- Collapsible Body -->
                    <div id="fund-{{ $loop->index }}" class="collapse">
                        <div class="card-body p-4 fund-card-body">
                        @php
                                $tables = [];
                                if($data['fund_name'] !== 'EU') {
                                    $tables[] = ['title' => $data['fund_name'], 'stats' => $data];
                                } else {
                                    $tables[] = ['title' => 'EU/OCA', 'stats' => $data['eu_oca']];
                                    $tables[] = ['title' => 'EU/Data Science', 'stats' => $data['eu_ds']];
                                }
                            @endphp

                            @foreach($tables as $table)
                                @if($data['fund_name'] === 'EU')
                                    <h6 class="fw-bold mt-3 mb-3" style="color:#000;">{{ $table['title'] }}</h6>
                                @endif

                                <div class="d-flex flex-wrap justify-content-between g-3">
                                    <!-- Each Card -->
                                    @php
                                        $cards = [
                                            ['title'=>'Total Trainees','value'=>$table['stats']['totalTrainees'],'male'=>$table['stats']['male']??0,'female'=>$table['stats']['female']??0],
                                            ['title'=>'Total Graduates','value'=>$table['stats']['totalGraduates'],'male'=>$table['stats']['maleGraduates']??0,'female'=>$table['stats']['femaleGraduates']??0],
                                            ['title'=>'Available Trainees','value'=>$table['stats']['availableTrainees'],'male'=>$table['stats']['maleAvailable']??0,'female'=>$table['stats']['femaleAvailable']??0],
                                            ['title'=>'Employed Trainees','value'=>$table['stats']['employedTrainees'],'male'=>$table['stats']['maleEmployed']??0,'female'=>$table['stats']['femaleEmployed']??0],
                                            ['title'=>'Employment Rate','value'=>$table['stats']['employmentRate'].'%','male'=>$table['stats']['maleEmploymentRate'].'%','female'=>$table['stats']['femaleEmploymentRate'].'%'],
                                        ];
                                    @endphp

                                    @foreach($cards as $card)
                                    <div class="flex-fill m-1" style="min-width:180px;">
                                        <div class="card text-center shadow-sm h-100">
                                            <div class="card-body d-flex flex-column justify-content-center">
                                                <h5>{{ $card['title'] }}</h5>
                                                <p class="card-text fw-bold">{{ $card['value'] }}</p>
                                                <small style="font-size: 1.10rem;">
                                                    <i class="fas fa-male" style="color: #1e90ff;"></i> {{ $card['male'] }}
                                                    |
                                                    <i class="fas fa-female" style="color: #ff69b4;"></i> {{ $card['female'] }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>




    <!-- QUICK LINKS -->
    <!-- your-view-name.blade.php -->
    <div class="popular-services">
        <div class="container pt-5">
            <!-- Header title + link to see all -->
            <!-- <div class="d-flex mb-3">
                        <h2 class="mb-0">QUICK LINKS</h2>
                    </div>
                    <div class="row">
                        @foreach ($academies as $academy)
                            <div class="col-12 col-md-4 mb-3 mb-md-3">
                                <div class="card border-light fixed-height">
                                    <img class="card-img-top img-fluid academy-image" src="{{asset('assets/images/' . $academy->academy_img) }}" alt="{{ $academy->name }}" width="416" height="122" loading="lazy"/>
                                    <div class="card-body">
                                        <h3>{{ $academy->name }}</h3>
                                        <p class="card-text" lang="zxx">
                                            {{ $academy->description }}
                                        </p>
                                        <a href="{{url('academy/' . $academy->slug) }}" class="o-link-arrow">Learn More</a>                        </div>
                                </div>
                            </div>
                        @endforeach
                    </div> -->
        </div>
    </div>

@endsection
@section('scripts')


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (btn) {
            btn.addEventListener("click", function () {
                let icon = btn.querySelector("i");
                let target = document.querySelector(btn.getAttribute("data-bs-target"));

                target.addEventListener("shown.bs.collapse", function () {
                    icon.classList.remove("fa-chevron-down");
                    icon.classList.add("fa-chevron-up");
                });

                target.addEventListener("hidden.bs.collapse", function () {
                    icon.classList.remove("fa-chevron-up");
                    icon.classList.add("fa-chevron-down");
                });
            });
        });
    });
var employmentRatesData = @json($chartData);
var chartData = @json($chartData);
// Employment Rate Chart
var ctxEmploymentRate = document.getElementById('employmentRateChart').getContext('2d');
var employmentRateChart = new Chart(ctxEmploymentRate, {
    type: 'bar', // Change to 'line' or other chart type as needed
    data: {
        labels: chartData.employmentRate.labels,
        datasets: [{
            label: 'Employment Rate',
            data: chartData.employmentRate.data,
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Customize as needed
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        // Customize chart options as needed
    }
});
</script>
<script>
// Ensure DOM is fully loaded
window.onload = function() {
    // Trainee Changes Chart
    var ctxTraineeChanges = document.getElementById('traineeChangesChart').getContext('2d');
    var traineeChangesChart = new Chart(ctxTraineeChanges, {
        type: 'line', // Change to 'bar' or other chart type as needed
        data: {
            labels: chartData.traineeChanges.labels,
            datasets: [{
                label: 'Trainee Changes',
                data: chartData.traineeChanges.data,
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {} // Simplified options, you can gradually add them back
    });
};
</script>


<script>
var dynamicLabels = @json($dynamicLabels);
</script>

<script>
$(document).ready(function($) {
    $('.count-number').counterUp({
        delay: 10,
        time: 10000
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('employmentRateChart').getContext('2d');
const employmentRateChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json(array_column($fundData, 'fund_name')),
        datasets: [{
            label: 'Employment Rate (%)',
            data: @json(array_column($fundData, 'employmentRate')),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                title: {
                    display: true,
                    text: 'Employment Rate (%)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Funds'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                enabled: true
            }
        },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
<script>
const ctx = document.getElementById('academyEmploymentChart').getContext('2d');
const academyEmploymentChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json(array_column($employmentData, 'academy')),
        datasets: [{
                label: 'Total Trainees',
                data: @json(array_column($employmentData, 'totalTrainees')),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Total Graduates',
                data: @json(array_column($employmentData, 'totalGraduates')),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Total Available',
                data: @json(array_column($employmentData, 'availableTrainees')),
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            },
            {
                label: 'Employed Trainees',
                data: @json(array_column($employmentData, 'employedTrainees')),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            //    {
            //         label: 'Employment Rate (%)',
            //         data: @json(array_column($employmentData, 'employmentRate')),
            //         backgroundColor: 'rgba(153, 102, 255, 0.6)',
            //         borderColor: 'rgba(153, 102, 255, 1)',
            //         borderWidth: 1,
            //         type: 'line'  // Employment Rate as a line
            //     },
            {
                label: 'Total Female',
                data: @json(array_column($employmentData, 'totalFemale')),
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            },
            {
                label: 'Total Male',
                data: @json(array_column($employmentData, 'totalMale')),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Count / Percentage'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Academies'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                enabled: true
            }
        },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>


<script src="{{asset('assets/js/map-data.js')}}"></script>
<script src="https://orangecodingacademy.com/layout/js/countrymap.js"></script>

@endsection
