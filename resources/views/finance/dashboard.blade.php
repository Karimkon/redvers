@extends('finance.layouts.app')

@section('title', 'Finance Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Finance Dashboard</h2>

    {{-- Filter Section --}}
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

    {{-- Revenue Summary --}}
    <div class="alert alert-success text-center fs-5 fw-bold">
        Total Revenue: UGX {{ number_format($totalRevenue) }}
    </div>

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


    {{-- Revenue Charts --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Payments by Day (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="paymentsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Revenue by Station</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Weekly Stats --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Weekly Financial Summary</div>
        <div class="card-body">
            <ul class="list-group">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
