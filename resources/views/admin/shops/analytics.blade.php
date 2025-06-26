@extends('admin.layouts.app')

@section('title', 'Shop Analytics - ' . $shop->name)

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 🔖 Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-bar-chart-line me-2"></i> {{ $shop->name }} Analytics
        </h4>
        <a href="{{ route('admin.low-stock-alerts.index') }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> View All Alerts
        </a>
    </div>

    {{-- 📅 Date Filter --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-3 align-self-end">
            <button class="btn btn-primary">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
    </form>

    {{-- 📊 Analytics Summary --}}
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success-subtle text-success">
                <div class="card-body">
                    <h6 class="fw-semibold">Total Parts Sold</h6>
                    <h4 class="fw-bold">{{ $totalSales }}</h4>
                    <small class="text-muted">From {{ $from }} to {{ $to }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary-subtle text-primary">
                <div class="card-body">
                    <h6 class="fw-semibold">Parts Received</h6>
                    <h4 class="fw-bold">{{ $totalReceived }}</h4>
                    <small class="text-muted">From {{ $from }} to {{ $to }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger-subtle text-danger">
                <div class="card-body">
                    <h6 class="fw-semibold">Low Stock Alerts</h6>
                    <h4 class="fw-bold">{{ $lowStockCount }}</h4>
                    <small class="text-muted">Unresolved Alerts</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning-subtle text-dark">
                <div class="card-body">
                    <h6 class="fw-semibold">Revenue</h6>
                    <h4 class="fw-bold">UGX {{ number_format($totalRevenue) }}</h4>
                    <small class="text-muted">Total sales value</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-dark-subtle text-dark">
                <div class="card-body">
                    <h6 class="fw-semibold">Profit</h6>
                    <h4 class="fw-bold">UGX {{ number_format($totalProfit) }}</h4>
                    <small class="text-muted">Total profit earned</small>
                    <div class="mt-2 text-end">
                        <a href="{{ route('admin.shops.profit.details', ['shop' => $shop->id, 'from' => $from, 'to' => $to]) }}" class="btn btn-outline-dark btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </div>

    {{-- 📈 Charts --}}
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">📈 Daily Parts Sold</div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">📦 Daily Stock Received</div>
                <div class="card-body">
                    <canvas id="stockChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">🥇 Top 10 Bestselling Parts</div>
            <div class="card-body">
                <canvas id="topPartsChart" height="100"></canvas>
            </div>
        </div>
    </div>


</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Parts Sold',
                data: @json($salesData),
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('stockChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Parts Received',
                data: @json($stockData),
                backgroundColor: '#0d6efd',
                borderRadius: 6,
                barThickness: 25
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('topPartsChart'), {
    type: 'pie',
    data: {
        labels: @json($topPartLabels),
        datasets: [{
            data: @json($topPartCounts),
            backgroundColor: [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                '#20c997', '#fd7e14', '#0dcaf0', '#198754', '#ff6384'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

</script>
@endpush
