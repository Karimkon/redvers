@extends('admin.layouts.app')

@section('title', 'Spare Shop Dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <!-- 🔹 Header + Filter -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="bi bi-shop-window me-2"></i> Spare Shop Performance
            </h3>
            <small class="text-muted">Monitor and compare shop activity between time ranges.</small>
        </div>
        <form method="GET" class="d-flex align-items-center gap-2">
            <input type="date" name="from" class="form-control" value="{{ $from }}">
            <input type="date" name="to" class="form-control" value="{{ $to }}">
            <button class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </form>
    </div>

    <!-- 📊 KPI Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Spare Shops</h6>
                    <h4 class="fw-bold">
                        <i class="bi bi-buildings me-1 text-primary"></i> {{ $summary['totalShops'] }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Stock Received</h6>
                    <h4 class="fw-bold">
                        <i class="bi bi-box-seam me-1 text-success"></i> {{ number_format($summary['totalStock']) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Items Sold</h6>
                    <h4 class="fw-bold">
                        <i class="bi bi-cart-check me-1 text-danger"></i> {{ number_format($summary['totalSales']) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- 🏆 Top Performing Shops -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
            <span>
                <i class="bi bi-star-fill me-2 text-warning"></i> Top Performing Shops
            </span>
            <span class="badge bg-secondary text-light">Range: {{ $from }} → {{ $to }}</span>
        </div>
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Shop</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topShops as $shop)
                        <tr>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-shop me-1 text-primary"></i> {{ $shop['name'] }}
                            </td>
                            <td>
                                <span class="badge bg-success fs-6">
                                    {{ number_format($shop['sales']) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                <i class="bi bi-emoji-frown me-1"></i> No sales data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
    