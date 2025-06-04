@extends('rider.layouts.app')

@section('title', 'My Swaps')

@section('content')
    <h2 class="mb-4">My Swap History</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Station</th>
                <th>Battery %</th>
                <th>Payable</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($swaps as $swap)
                <tr>
                    <td>{{ $swap->swapped_at->format('d M Y H:i') }}</td>
                    <td>{{ $swap->station->name ?? '—' }}</td>
                    <td>{{ number_format($swap->percentage_difference, 2) }}%</td>
                    <td>{{ number_format($swap->payable_amount) }} UGX</td>
                    <td>{{ ucfirst($swap->payment_method ?? 'N/A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No swaps found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $swaps->links() }}
    </div>
@endsection
