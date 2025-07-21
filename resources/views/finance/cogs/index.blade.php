@extends('finance.layouts.app')

@section('title', 'COGS List')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">🧾 Cost of Goods Sold (COGS)</h4>

    <a href="{{ route('finance.cogs.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Add COGS Entry
    </a>

    <div class="table-responsive card p-3 shadow-sm">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Unit Cost</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cogs as $i => $entry)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $entry->product->name ?? 'N/A' }}</td>
                    <td>UGX {{ number_format($entry->unit_cost) }}</td>
                    <td>{{ $entry->quantity }}</td>
                    <td>UGX {{ number_format($entry->unit_cost * $entry->quantity) }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('finance.cogs.show', $entry) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('finance.cogs.edit', $entry) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('finance.cogs.destroy', $entry) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this COGS entry?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $cogs->links() }}
        </div>
    </div>
</div>
@endsection
