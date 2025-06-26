<!-- resources/views/inventory/sales/index.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Sales')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Sales Records</h4>
        <a href="{{ route('inventory.sales.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> New Sale
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Part</th>
                    <th>Quantity</th>
                    <th>Selling Price (UGX)</th>
                    <th>Total Price</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->part->name }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>{{ number_format($sale->selling_price) }}</td>
                        <td>UGX {{ number_format($sale->total_price) }}</td>
                        <td>{{ $sale->customer_name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('inventory.sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Delete this sale?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No sales recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sales->links() }}
</div>
@endsection
