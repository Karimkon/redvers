@extends('admin.layouts.app')

@section('title', 'Stations')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-geo-alt-fill me-2"></i> Manage Stations
        </h4>
        <a href="{{ route('admin.stations.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Add New Station
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search Input --}}
    <div class="mb-3">
        <input type="text" id="stationSearch" class="form-control shadow-sm" placeholder="Search by station name, coordinates...">
    </div>

    {{-- Stations Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0" id="stationsTable">
                    <thead class="table-light text-center">
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
                        <tr class="text-center">
                            <td>{{ $station->id }}</td>
                            <td class="text-start">{{ $station->name }}</td>
                            <td>{{ $station->latitude }}</td>
                            <td>{{ $station->longitude }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.stations.show', $station) }}" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.stations.destroy', $station) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this station?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $stations->links() }}
    </div>
</div>

{{-- Live Search Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("stationSearch");
        const rows = document.querySelectorAll("#stationsTable tbody tr");

        input.addEventListener("input", function () {
            const search = this.value.toLowerCase();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? "" : "none";
            });
        });
    });
</script>
@endsection
