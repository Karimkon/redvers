@extends('admin.layouts.app')

@section('title', 'Agents')

@section('content')
<div class="container-fluid px-3 px-md-4">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-person-badge me-2"></i> Manage Agents
        </h3>
        <a href="{{ route('admin.agents.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Register Agent
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Form --}}
    <form method="GET" id="filterForm" class="row gy-2 gx-2 align-items-center mb-4">
        @csrf
        <div class="col-12 col-md-5">
            <input type="text" name="search" id="searchInput" class="form-control shadow-sm" placeholder="Search by name, phone, or email" value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-4">
            <select name="station_id" id="stationFilter" class="form-select shadow-sm">
                <option value="">All Stations</option>
                @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ request('station_id') == $station->id ? 'selected' : '' }}>
                        {{ $station->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 d-grid">
            <button type="submit" class="btn btn-primary shadow-sm">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
    </form>

    {{-- Agent Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Station</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agents as $agent)
                            <tr class="text-center">
                                <td>{{ $agent->id }}</td>
                                <td class="text-start">{{ $agent->name }}</td>
                                <td>{{ $agent->phone }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>{{ $agent->station->name ?? '—' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.agents.show', $agent) }}" class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this agent?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle"></i> No agents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $agents->withQueryString()->links() }}
    </div>
</div>

{{-- Auto Filter Script --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const stationFilter = document.getElementById('stationFilter');
    const filterForm = document.getElementById('filterForm');

    if (searchInput && stationFilter) {
        let timeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => filterForm.submit(), 600);
        });

        stationFilter.addEventListener('change', function () {
            filterForm.submit();
        });
    }
</script>
@endsection
