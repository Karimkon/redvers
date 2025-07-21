@extends('finance.layouts.app')

@section('title', 'Depreciation Records')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4 text-primary">📉 Depreciation Records</h4>

    <div class="mb-3 text-end">
        <a href="{{ route('finance.depreciations.create') }}" class="btn btn-success btn-sm">➕ Add Depreciation</a>
    </div>

    @if($depreciations->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Initial Value (UGX)</th>
                        <th>Rate (%)</th>
                        <th>Lifespan</th>
                        <th>Start Date</th>
                        <th>Summary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($depreciations as $item)
                    @php
                        $total = $item->initial_value * ($item->depreciation_rate / 100);
                        $monthly = ($item->lifespan_months > 0) ? $total / $item->lifespan_months : 0;
                        $final = $item->initial_value - $total;
                    @endphp
                    <tr>
                        <td>{{ $item->product->name ?? '—' }}</td>
                        <td>{{ number_format($item->initial_value) }}</td>
                        <td>{{ $item->depreciation_rate }}%</td>
                        <td>{{ $item->lifespan_months ?? '—' }} months</td>
                        <td>{{ $item->start_date->format('d M Y') }}</td>
                        <td>
                            <small>
                                <strong>Monthly:</strong> UGX {{ number_format($monthly, 2) }} <br>
                                <strong>Total:</strong> UGX {{ number_format($total, 2) }} <br>
                                <strong>Final:</strong> UGX {{ number_format($final, 2) }}
                            </small>
                        </td>
                        <td>
                            <a href="{{ route('finance.depreciations.show', $item) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('finance.depreciations.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('finance.depreciations.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $depreciations->links() }}
    @else
        <div class="alert alert-info">No depreciation records yet.</div>
    @endif
</div>
@endsection
