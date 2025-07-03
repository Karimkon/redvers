@extends('admin.layouts.app')

@section('title', 'Profit Details - ' . $shop->name)

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📊 Profit Details for <span class="text-primary">{{ $shop->name }}</span></h4>
        <a href="{{ route('admin.shops.analytics', $shop) }}" class="btn btn-outline-dark">← Back to Analytics</a>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-start border-4 border-primary">
                <div class="card-body">
                    <h6 class="text-muted">💰 Total Money Invested (Only sold)</h6>
                    <h4 class="fw-bold mb-0 text-dark">UGX {{ number_format($totalCost) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <h6 class="text-muted">📈 Total Profit</h6>
                    <h4 class="fw-bold mb-0 text-success">UGX {{ number_format($totalProfit) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Part</th>
                    <th>Qty</th>
                    <th>Buying Price</th>
                    <th>Selling Price</th>
                    <th>Total Price</th>
                    <th>Profit</th>
                    <th>Customer</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                <tr>
                    <td>{{ $sale->part->name ?? '-' }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>UGX {{ number_format($sale->cost_price) }}</td>
                    <td>UGX {{ number_format($sale->selling_price) }}</td>
                    <td>UGX {{ number_format($sale->total_price) }}</td>
                    <td class="fw-bold text-success">UGX {{ number_format($sale->profit) }}</td>
                    <td>{{ $sale->customer_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No sales found for this period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-center">
        <nav>
            <ul class="pagination pagination-sm">
                {{ $sales->onEachSide(1)->links('pagination::bootstrap-5') }}
            </ul>
        </nav>
    </div>
</div>
@endsection
