@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Hero Logo --}}
    <div class="dashboard-hero text-center mb-4 animate__animated animate__fadeInDown">
        <img src="{{ asset('images/favicon.png') }}" alt="Redvers Logo" class="dashboard-logo">
    </div>

    {{-- Filters --}}
    <form method="GET" class="dashboard-filter row g-3 align-items-end mb-4 animate__animated animate__fadeInUp animate__delay-1s">
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
            <button class="btn btn-gradient w-100"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </form>

    {{-- Revenue --}}
    <div class="dashboard-revenue alert alert-glass text-center animate__animated animate__fadeInUp animate__delay-2s">
        💰 Total Revenue: UGX {{ number_format($totalRevenue) }}
    </div>
    <div class="dashboard-breakdown alert alert-glass-info text-center animate__animated animate__fadeInUp animate__delay-2s">
    Breakdown:
    <span class="fw-bold text-success">UGX {{ number_format($totalPayments) }}</span> from swaps +
    <span class="fw-bold text-primary">UGX {{ number_format($totalPromotions) }}</span> from promotions +
    <span class="fw-bold" style="color: #ffba08; text-shadow: 1px 1px 2px #000;">UGX {{ number_format($totalMotorcyclePayments) }}</span> from motorcycle payments

</div>


    {{-- Quick Stats --}}
    <div class="row justify-content-center mb-4 animate__animated animate__fadeInUp animate__delay-3s">
        @foreach ([['title'=>'Riders','count'=>$ridersCount,'icon'=>'bi-person-fill','color'=>'#0d6efd'],
            ['title'=>'Stations','count'=>$stationsCount,'icon'=>'bi-geo-alt-fill','color'=>'#22c55e'],
            ['title'=>'Swaps','count'=>$swapsCount,'icon'=>'bi-arrow-left-right','color'=>'#facc15'],
            ['title'=>'Agents','count'=>$agentsCount,'icon'=>'bi-people-fill','color'=>'#06b6d4'],
            ['title'=>'Payments','count'=>$paymentsCount,'icon'=>'bi-cash-stack','color'=>'#ef4444']] as $stat)
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stat-card animate__animated animate__zoomIn" style="
            background: linear-gradient(135deg, {{ $stat['color'] }}, {{ $stat['color'] }}cc);
            color: white;">
            <div class="card-body text-center">
                <i class="bi {{ $stat['icon'] }} fs-2 mb-2"></i>
                <h4 class="fw-bold">{{ $stat['count'] }}</h4>
                <p class="mb-0">{{ $stat['title'] }}</p>
            </div>
        </div>
    </div>
    @endforeach

    </div>

    {{-- Battery Status --}}
    <h5 class="fw-bold mb-3 animate__animated animate__fadeInLeft"><i class="bi bi-battery-charging text-warning"></i> Battery Status Overview</h5>
    <div class="row mb-4">
        @foreach([
    ['label' => 'In Stock', 'key' => 'in_stock', 'icon' => 'bi-battery-full', 'color' => '#0d6efd'],
    ['label' => 'In Use', 'key' => 'in_use', 'icon' => 'bi-battery-half', 'color' => '#22c55e'],
    ['label' => 'Charging', 'key' => 'charging', 'icon' => 'bi-battery-charging', 'color' => '#facc15'],
    ['label' => 'Damaged', 'key' => 'damaged', 'icon' => 'bi-exclamation-octagon-fill', 'color' => '#ef4444'],
] as $status)
<div class="col-lg-3 col-md-6 col-sm-6 mb-3">
    <div class="card battery-card animate__animated animate__flipInX" style="
        background: linear-gradient(135deg, {{ $status['color'] }}, {{ $status['color'] }}cc);
        color: white; min-height: 120px;">
        <div class="card-body text-center d-flex flex-column justify-content-center">
            <i class="bi {{ $status['icon'] }} fs-2 mb-2"></i>
            <h4 class="fw-bold">{{ $batteryStatusCounts[$status['key']] ?? 0 }}</h4>
            <p class="mb-0">{{ $status['label'] }}</p>
        </div>
    </div>
</div>
@endforeach
{{-- Compact Total Due Amount Box --}}
<div class="col-md-6 mb-4 mx-auto">
    <div class="card border-start border-danger border-4 shadow-sm">
        <div class="card-body text-center">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                <i class="bi bi-exclamation-circle-fill text-danger fs-3"></i>
                <h5 class="mb-0 text-danger fw-semibold">Total Motorcycle Due from All Riders</h5>
            </div>
            <h4 class="fw-bold text-dark">UGX {{ number_format($totalDue) }}</h4>
        </div>
    </div>
</div>


    </div>

    {{-- Charts --}}
    <div class="row mb-4 animate__animated animate__fadeInUp animate__delay-4s">
        <div class="col-md-6 mb-4">
            <div class="card chart-card">
                <div class="card-header chart-header" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: white;">
📊 Swaps by Day (Last 7 Days)
                </div>
                <div class="card-body">
                    <canvas id="swapsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card chart-card">
                <div class="card-header chart-header">💳 Payments by Day (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="paymentsChart"></canvas>
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
