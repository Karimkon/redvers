<!-- resources/views/inventory/stock_entries/index.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Stock Entries')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Stock Entries</h4>
        <a href="{{ route('inventory.stock-entries.create') }}" class="btn btn-success">
            <i class="bi bi-box-arrow-down me-1"></i> Add Entry
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th>Part</th>
                    <th>Quantity</th>
                    <th>Cost Price (UGX)</th>
                    <th>Date Received</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->part->name }}</td>
                        <td>{{ $entry->quantity }}</td>
                        <td>{{ number_format($entry->cost_price) }}</td>
                        <td>{{ \Carbon\Carbon::parse($entry->received_at)->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('inventory.stock-entries.destroy', $entry) }}" method="POST" onsubmit="return confirm('Delete this stock entry?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No stock entries recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $entries->links() }}
</div>
@endsection
