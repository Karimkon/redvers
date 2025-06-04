@extends('agent.layouts.app')

@section('title', 'Swap History')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <h2 class="mb-0">Detailed Swap History</h2>

    <form method="GET" class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
        <input type="text" name="search" class="form-control" placeholder="Search name, phone or plate" value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-search"></i> Search
        </button>
    </form>
</div>

@if ($swaps->isEmpty())
    <div class="alert alert-warning">No swap history found.</div>
@else
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-dark">
    <tr>
        <th>Date</th>
        <th>Rider</th>
        <th>Motorcycle</th> {{-- New --}}
        <th>Station</th>
        <th>Battery %</th>
        <th>Amount</th>
        <th>Method</th>
    </tr>
</thead>
<tbody>
    @foreach ($swaps as $swap)
        <tr>
            <td>{{ $swap->swapped_at->format('Y-m-d H:i') }}</td>
            <td>{{ $swap->riderUser->name ?? 'N/A' }}</td>
            <td>
                {{ $swap->motorcycleUnit->number_plate ?? '—' }}
                <br><small>ID: {{ $swap->motorcycle_unit_id }}</small>
            </td>
            <td>{{ $swap->station->name ?? 'N/A' }}</td>
            <td>{{ $swap->percentage_difference }}%</td>
            <td>UGX {{ number_format($swap->payable_amount) }}</td>
            <td>{{ strtoupper($swap->payment_method ?? '-') }}</td>
        </tr>
    @endforeach
</tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $swaps->withQueryString()->links() }}
    </div>
@endif
@endsection
