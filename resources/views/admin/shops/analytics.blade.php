@extends('admin.layouts.app')

@section('title', 'Shop Analytics - ' . $shop->name)

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- 🔖 Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="fw-bold mb-1">
                <i class="bi bi-bar-chart-line me-2"></i> {{ $shop->name }} Analytics
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.shops.index') }}">Shops</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.low-stock-alerts.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> View All Alerts
        </a>
    </div>

    {{-- 📅 Date Filter Card --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <h5 class="card-title mb-3 text-dark">
                <i class="bi bi-funnel me-1"></i> Filter Analytics
            </h5>
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">From Date</label>
                    <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">To Date</label>
                    <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Investment Type</label>
                    <select name="investment_type" class="form-select form-select-sm">
                        <option value="lifetime" {{ request('investment_type') == 'lifetime' ? 'selected' : '' }}>
                            Lifetime Investment
                        </option>
                        <option value="inventory" {{ request('investment_type') == 'inventory' ? 'selected' : '' }}>
                            Current Inventory Value
                        </option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm">
                        <i class="bi bi-filter-circle me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 📊 Analytics Summary --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Parts Sold</span>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $totalSales }}</h3>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success p-2">
                            <i class="bi bi-cart-check fs-5"></i>
                        </span>
                    </div>
                    <p class="small text-muted mb-0">From {{ $from }} to {{ $to }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Parts Received</span>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $totalReceived }}</h3>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                            <i class="bi bi-box-seam fs-5"></i>
                        </span>
                    </div>
                    <p class="small text-muted mb-0">From {{ $from }} to {{ $to }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Low Stock</span>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $lowStockCount }}</h3>
                        </div>
                        <span class="badge bg-danger bg-opacity-10 text-danger p-2">
                            <i class="bi bi-exclamation-triangle fs-5"></i>
                        </span>
                    </div>
                    <p class="small text-muted mb-0">Unresolved Alerts</p>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Investment</span>
                            <h3 class="mt-2 mb-0 fw-bold">UGX {{ number_format($totalInvested) }}</h3>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info p-2">
                            <i class="bi bi-cash-stack fs-5"></i>
                        </span>
                    </div>
                    <p class="small text-muted mb-0">
                        {{ $investmentType === 'inventory' ? 'Current stock value' : 'Lifetime investment' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Revenue</span>
                            <h3 class="mt-2 mb-0 fw-bold">UGX {{ number_format($totalRevenue) }}</h3>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning p-2">
                            <i class="bi bi-graph-up fs-5"></i>
                        </span>
                    </div>
                    <p class="small text-muted mb-0">Total sales value</p>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Profit</span>
                            <h3 class="mt-2 mb-0 fw-bold">UGX {{ number_format($totalProfit) }}</h3>
                        </div>
                        <span class="badge bg-dark bg-opacity-10 text-dark p-2">
                            <i class="bi bi-currency-dollar fs-5"></i>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-2">
                        <p class="small text-muted mb-0">Total profit</p>
                        <a href="{{ route('admin.shops.profit.details', ['shop' => $shop->id, 'from' => $from, 'to' => $to]) }}" 
                           class="btn btn-link btn-sm p-0 text-decoration-none">
                            Details <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 📈 Charts Section --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📈 Sales & Stock Movement</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear me-1"></i> Options
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartDropdown">
                            <li><a class="dropdown-item" href="#">Export Data</a></li>
                            <li><a class="dropdown-item" href="#">Print Chart</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="combinedChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">🥇 Top Selling Parts</h5>
                </div>
                <div class="card-body">
                    <canvas id="topPartsChart" height="300"></canvas>
                </div>
                <div class="card-footer bg-white border-top small text-muted">
                    Showing top 10 parts by sales volume
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Data Section --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">📊 Daily Parts Sold</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">📦 Daily Stock Received</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);
    const salesData = @json($salesData);
    const stockData = @json($stockData);
    const topPartLabels = @json($topPartLabels);
    const topPartCounts = @json($topPartCounts);

    // Combined Chart (Sales & Stock)
    new Chart(document.getElementById('combinedChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Parts Sold',
                    data: salesData,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    borderRadius: 4,
                    order: 2
                },
                {
                    label: 'Parts Received',
                    data: stockData,
                    borderColor: 'rgba(13, 110, 253, 1)',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    type: 'line',
                    order: 1,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(13, 110, 253, 1)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Top Parts Pie Chart
    new Chart(document.getElementById('topPartsChart'), {
        type: 'doughnut',
        data: {
            labels: topPartLabels,
            datasets: [{
                data: topPartCounts,
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#5a5c69', '#858796', '#3a3b45', '#f8f9fc', '#d1d3e2'
                ],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 16,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Sales Line Chart
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Parts Sold',
                data: salesData,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#198754',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Stock Bar Chart
    new Chart(document.getElementById('stockChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Parts Received',
                data: stockData,
                backgroundColor: 'rgba(13, 110, 253, 0.8)',
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush