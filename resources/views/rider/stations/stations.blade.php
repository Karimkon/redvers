@extends('rider.layouts.app')

@section('title', 'Nearby Battery Stations')

@section('content')
    <h4 class="mb-3">📍 Nearby Battery Stations</h4>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Station Name</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($stations as $station)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $station->name }}</td>
                <td>{{ $station->latitude }}</td>
                <td>{{ $station->longitude }}</td>
                <td>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $station->latitude }},{{ $station->longitude }}"
                       target="_blank"
                       class="btn btn-sm btn-primary">
                        Get Directions
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No stations found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

    </div>
@endsection
