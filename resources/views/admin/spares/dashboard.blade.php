<!-- resources/views/admin/spares/dashboard.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Spare Shop Dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-bar-chart-line me-2"></i> Spare Shop Performance
        </h3>
        <form method="GET" class="d-flex align-items-center gap-2">
            <input type="date" name="from" class="form-control" value="{{ $from }}">
            <input type="date" name="to" class="form-control" value="{{ $to }}">
            <button class="btn btn-primary">Filter</button>
        </form>
    </div>

    <!-- 🔢 Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Spare Shops</h6>
                    <h4 class="fw-bold">{{ $summary['totalShops'] }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Stock Received</h6>
                    <h4 class="fw-bold">{{ number_format($summary['totalStock']) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Items Sold</h6>
                    <h4 class="fw-bold">{{ number_format($summary['totalSales']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- 🏆 Top Performing Shops -->
    <div class="card shadow-sm">
        <div class="card-header fw-semibold">
            Top Performing Shops ({{ $from }} to {{ $to }})
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Shop</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topShops as $shop)
                        <tr>
                            <td>{{ $shop['name'] }}</td>
                            <td class="fw-semibold">{{ $shop['sales'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No sales data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
