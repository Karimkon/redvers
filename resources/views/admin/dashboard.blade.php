@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Animated Logo --}}
    <div class="text-center mb-4">
        <img src="{{ asset('images/redvers.jpeg') }}"
             alt="Redvers Logo"
             style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #dee2e6; animation: pulseLogo 2s infinite;"
             class="shadow">
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="mb-4 row g-3">
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
        <div class="col-md-3 align-self-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    {{-- Total Revenue Display --}}
    <div class="alert alert-success text-center fs-5 fw-bold">
        Total Revenue: UGX {{ number_format($totalRevenue) }}
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-4">
        @foreach ([
            ['title' => 'Riders', 'count' => $ridersCount, 'color' => 'primary'],
            ['title' => 'Stations', 'count' => $stationsCount, 'color' => 'success'],
            ['title' => 'Swaps', 'count' => $swapsCount, 'color' => 'warning'],
            ['title' => 'Agents', 'count' => $agentsCount, 'color' => 'info'],
            ['title' => 'Payments', 'count' => $paymentsCount, 'color' => 'danger'],
        ] as $card)
        <div class="col-md-2 mb-3">
            <div class="card text-white bg-{{ $card['color'] }} shadow-sm">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $card['count'] }}</h4>
                    <p class="card-text">{{ $card['title'] }}</p>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Battery Status Overview --}}
<div class="row mb-4">
    <h5 class="mb-3 ps-3">Battery Status Overview</h5>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary shadow-sm text-center">
            <div class="card-body">
                <h4 class="card-title">{{ $batteryStatusCounts['in_stock'] ?? 0 }}</h4>
                <p class="card-text">In Stock</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success shadow-sm text-center">
            <div class="card-body">
                <h4 class="card-title">{{ $batteryStatusCounts['in_use'] ?? 0 }}</h4>
                <p class="card-text">In Use</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning shadow-sm text-center">
            <div class="card-body">
                <h4 class="card-title">{{ $batteryStatusCounts['charging'] ?? 0 }}</h4>
                <p class="card-text">Charging</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger shadow-sm text-center">
            <div class="card-body">
                <h4 class="card-title">{{ $batteryStatusCounts['damaged'] ?? 0 }}</h4>
                <p class="card-text">Damaged</p>
            </div>
        </div>
    </div>
</div>

    </div>

    {{-- Charts --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Swaps by Day (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="swapsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Payments by Day (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="paymentsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue + Averages --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Revenue by Station</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Weekly Averages</div>
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

@push('scripts')
<style>
    @keyframes pulseLogo {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.85; transform: scale(1.08); }
    }
</style>

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
