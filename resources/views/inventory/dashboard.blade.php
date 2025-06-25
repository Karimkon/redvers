<!-- resources/views/inventory/dashboard.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Inventory Dashboard')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Spare Parts Inventory Overview</h4>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Parts</h6>
                    <h4 class="fw-bold">{{ $totalParts }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Stock Received</h6>
                    <h4 class="fw-bold">{{ number_format($totalReceived) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Items Sold</h6>
                    <h4 class="fw-bold">{{ number_format($totalSold) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Low Stock Alerts</h6>
                    <h4 class="fw-bold text-danger">{{ $lowStock->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔝 Top Selling Parts -->
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold">
            Top Selling Parts
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Part</th>
                        <th>Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSelling as $item)
                        <tr>
                            <td>{{ $item->part->name }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted">No sales yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ⚠️ Low Stock Parts -->
    <div class="card shadow-sm">
        <div class="card-header fw-semibold text-danger">
            Low Stock Warnings
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
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
                        <tr><td colspan="2" class="text-center text-muted">No low stock parts.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
