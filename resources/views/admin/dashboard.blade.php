@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Animated Logo --}}
    <div class="text-center mb-4">
        <img src="{{ asset('images/favicon.png') }}"
             alt="Redvers Logo"
             style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #dee2e6; animation: pulseLogo 2s infinite;"
             class="shadow">
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Quick Filter</label>
            <select name="period" class="form-select" onchange="this.form.submit()">
                <option value="">-- Select --</option>
                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
        </div>
    </form>

    {{-- Total Revenue Display --}}
    <div class="alert alert-success d-flex justify-content-center align-items-center fs-5 fw-bold rounded-pill">
        💰 Total Revenue: UGX {{ number_format($totalRevenue) }}
    </div>

    {{-- Quick Stats --}}
    <div class="row justify-content-center mb-4">
    @foreach ([
        ['title' => 'Riders', 'count' => $ridersCount, 'color' => 'primary', 'icon' => 'bi-person-fill'],
        ['title' => 'Stations', 'count' => $stationsCount, 'color' => 'success', 'icon' => 'bi-geo-alt-fill'],
        ['title' => 'Swaps', 'count' => $swapsCount, 'color' => 'warning', 'icon' => 'bi-arrow-left-right'],
        ['title' => 'Agents', 'count' => $agentsCount, 'color' => 'info', 'icon' => 'bi-people-fill'],
        ['title' => 'Payments', 'count' => $paymentsCount, 'color' => 'danger', 'icon' => 'bi-cash-stack'],
    ] as $card)
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3 d-flex justify-content-center">
        <div class="card text-white bg-{{ $card['color'] }} shadow-sm w-100 text-center">
            <div class="card-body">
                <i class="bi {{ $card['icon'] }} fs-2 mb-2"></i>
                <h4 class="card-title">{{ $card['count'] }}</h4>
                <p class="card-text mb-0">{{ $card['title'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>


    {{-- Battery Status --}}
    <h5 class="mb-3"><i class="bi bi-battery-charging text-warning"></i> Battery Status Overview</h5>
    <div class="row mb-4">
        @foreach([
            ['label' => 'In Stock', 'key' => 'in_stock', 'color' => 'primary'],
            ['label' => 'In Use', 'key' => 'in_use', 'color' => 'success'],
            ['label' => 'Charging', 'key' => 'charging', 'color' => 'warning'],
            ['label' => 'Damaged', 'key' => 'damaged', 'color' => 'danger'],
        ] as $status)
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-{{ $status['color'] }} shadow-sm text-center">
                <div class="card-body">
                    <h4 class="card-title">{{ $batteryStatusCounts[$status['key']] ?? 0 }}</h4>
                    <p class="card-text">{{ $status['label'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">📊 Swaps by Day (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="swapsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">💳 Payments by Day (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="paymentsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue + Averages --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">🏬 Revenue by Station</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">📈 Weekly Averages</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Avg Swaps/Day:</span> <strong>{{ $weeklyAverages['swaps'] }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Avg Payments/Day:</span> <strong>{{ $weeklyAverages['payments'] }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Top Station:</span> <strong>{{ $topStation ?? 'N/A' }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    @keyframes pulseLogo {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.85; transform: scale(1.08); }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const swapsChart = new Chart(document.getElementById('swapsChart'), {
        type: 'bar',
        data: {
            labels: @json($swapStats['labels']),
            datasets: [{
                label: 'Swaps',
                data: @json(array_map('intval', $swapStats['counts'])),
                backgroundColor: '#007bff',
                borderRadius: 6,
                barThickness: 25
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.raw} swap${ctx.raw !== 1 ? 's' : ''}`
                    }
                }
            }
        }
    });

    const paymentsChart = new Chart(document.getElementById('paymentsChart'), {
        type: 'line',
        data: {
            labels: @json($paymentStats['labels']),
            datasets: [{
                label: 'Payments (UGX)',
                data: @json($paymentStats['amounts']),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.15)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'UGX ' + value.toLocaleString()
                    }
                }
            }
        }
    });

    const revenueChart = new Chart(document.getElementById('revenueChart'), {
        type: 'pie',
        data: {
            labels: @json(array_keys($revenueByStation)),
            datasets: [{
                data: @json(array_values($revenueByStation)),
                backgroundColor: ['#ffc107', '#17a2b8', '#dc3545', '#6f42c1', '#20c997']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
