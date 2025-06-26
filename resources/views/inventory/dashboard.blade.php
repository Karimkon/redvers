@extends('inventory.layouts.app')

@section('title', 'Inventory Dashboard')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4 text-primary">
        <i class="bi bi-graph-up-arrow me-2"></i> Spare Parts Inventory Overview
    </h4>

    <!-- 🎯 KPI Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center">
                <div class="card-body">
                    <div class="text-muted mb-1">Total Parts</div>
                    <h3 class="fw-bold text-dark">{{ $totalParts }}</h3>
                    <i class="bi bi-box-seam fs-4 text-secondary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center">
                <div class="card-body">
                    <div class="text-muted mb-1">Stock Received</div>
                    <h3 class="fw-bold text-success">{{ number_format($totalReceived) }}</h3>
                    <i class="bi bi-truck fs-4 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center">
                <div class="card-body">
                    <div class="text-muted mb-1">Items Sold</div>
                    <h3 class="fw-bold text-primary">{{ number_format($totalSold) }}</h3>
                    <i class="bi bi-currency-exchange fs-4 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center">
                <div class="card-body">
                    <div class="text-muted mb-1">Low Stock</div>
                    <h3 class="fw-bold text-danger">{{ $lowStock->count() }}</h3>
                    <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 🏆 Top Selling Parts -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-white fw-semibold border-0 fs-6">
            <i class="bi bi-star-fill text-warning me-1"></i> Top Selling Parts
        </div>
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Part</th>
                        <th>Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSelling as $item)
                        <tr>
                            <td class="fw-medium">{{ $item->part->name }}</td>
                            <td><span class="badge bg-success">{{ $item->total }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted">No sales data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🚨 Low Stock Alerts -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white fw-semibold border-0 fs-6 text-danger">
            <i class="bi bi-exclamation-circle-fill me-1"></i> Low Stock Warnings
        </div>
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Part</th>
                        <th>Current Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStock as $part)
                        <tr>
                            <td>{{ $part->name }}</td>
                            <td class="text-danger fw-bold">{{ $part->stock }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted">All parts are sufficiently stocked.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
