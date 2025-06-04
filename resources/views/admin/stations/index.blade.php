@extends('admin.layouts.app')

@section('title', 'Stations')

@section('content')
<div class="container">
    <h2 class="mb-4">Stations</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.stations.create') }}" class="btn btn-primary mb-3">Add New Station</a>

    {{-- Instant Search --}}
    <input type="text" id="stationSearch" class="form-control mb-3" placeholder="Search stations...">

    <div class="table-responsive bg-white shadow-sm p-3 rounded">
        <table class="table table-bordered table-hover" id="stationsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stations as $station)
                <tr>
                    <td>{{ $station->id }}</td>
                    <td>{{ $station->name }}</td>
                    <td>{{ $station->latitude }}</td>
                    <td>{{ $station->longitude }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('admin.stations.show', $station) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.stations.destroy', $station) }}" method="POST" onsubmit="return confirm('Delete this station?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $stations->links() }}
</div>

{{-- Inline script to auto-filter --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("stationSearch");
        const rows = document.querySelectorAll("#stationsTable tbody tr");

        input.addEventListener("input", function () {
            const search = this.value.toLowerCase();
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(search) ? "" : "none";
            });
        });
    });
</script>
@endsection
