@extends('admin.layouts.app')

@section('title', 'Profit Details - ' . $shop->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Profit Details for {{ $shop->name }}</h4>
        <a href="{{ route('admin.shops.analytics', $shop) }}" class="btn btn-outline-dark">← Back to Analytics</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Part</th>
                <th>Qty</th>
                <th>Selling Price</th>
                <th>Cost Price</th>
                <th>Total Price</th>
                <th>Profit</th>
                <th>Customer</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale->part->name ?? '-' }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ number_format($sale->selling_price) }}</td>
                <td>{{ number_format($sale->cost_price) }}</td>
                <td>UGX {{ number_format($sale->total_price) }}</td>
                <td class="fw-bold text-success">UGX {{ number_format($sale->profit) }}</td>
                <td>{{ $sale->customer_name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3 text-end">
        <h5 class="fw-bold">Total Profit: UGX {{ number_format($totalProfit) }}</h5>
    </div>

    {{ $sales->withQueryString()->links() }}
</div>
@endsection
