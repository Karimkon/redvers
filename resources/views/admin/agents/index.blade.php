@extends('admin.layouts.app')

@section('title', 'Agents')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <h2 class="mb-0">Agents</h2>
         <a href="{{ route('admin.agents.create') }}" class="btn btn-success">
            <i class="bi bi-person-plus me-1"></i> Register New Agent
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Bar --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search by name, phone or email" value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-4">
            <select id="stationFilter" name="station_id" class="form-select">
                <option value="">All Stations</option>
                @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ request('station_id') == $station->id ? 'selected' : '' }}>
                        {{ $station->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-1"></i> Filter
            </button>
        </div>
    </form>

    {{-- Agent Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Station</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                            <tr>
                                <td>{{ $agent->id }}</td>
                                <td>{{ $agent->name }}</td>
                                <td>{{ $agent->phone }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>{{ $agent->station->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('admin.agents.show', $agent) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Delete this agent?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bi bi-trash"></i>
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

    <div class="mt-3">
        {{ $agents->withQueryString()->links() }}
    </div>
</div>

{{-- Auto Filter Script --}}
<script>
    let searchInput = document.getElementById('searchInput');
    let stationFilter = document.getElementById('stationFilter');

    if (searchInput && stationFilter) {
        let timeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => document.forms[0].submit(), 800);
        });

        stationFilter.addEventListener('change', function () {
            document.forms[0].submit();
        });
    }
</script>
@endsection
