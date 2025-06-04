@extends('admin.layouts.app')

@section('title', 'Battery History')

@section('content')
    <h2>Battery History: {{ $battery->serial_number }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Swap Date</th>
                <th>Rider</th>
                <th>From Station</th>
                <th>To Station</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($swaps as $index => $swap)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $swap->swapped_at }}</td>
                    <td>{{ $swap->swap->riderUser->name ?? '-' }}</td>
                    <td>{{ $swap->from_station_id }}</td>
                    <td>{{ $swap->to_station_id }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No history found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
