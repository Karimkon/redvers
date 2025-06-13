@extends('admin.layouts.app')

@section('title', 'Batteries')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
    <h2 class="mb-0">Battery List</h2>
    <a href="{{ route('admin.batteries.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add New Battery
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-md-4">
        <label for="station_id" class="form-label">Filter by Station:</label>
        <select name="station_id" id="station_id" onchange="this.form.submit()" class="form-select">
            <option value="">-- All Stations --</option>
            @foreach($stations as $station)
                <option value="{{ $station->id }}" {{ request('station_id') == $station->id ? 'selected' : '' }}>
                    {{ $station->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
    <label for="search" class="form-label">Search Serial Number:</label>
    <input type="text" name="search" id="search" class="form-control"
           placeholder="e.g. redversbattery7" value="{{ request('search') }}">
</div>
<div class="col-md-2">
    <button class="btn btn-primary w-100" type="submit">Filter</button>
</div>

</form>


<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Serial Number</th>
                        <th>Status</th>
                        <th>Station</th>
                        <th>Current Rider</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batteries as $battery)
                    <tr>
                        <td>{{ $battery->serial_number }}</td>
                        <td>{{ ucfirst($battery->status) }}</td>
                        <td>{{ $battery->currentStation->name ?? '—' }}</td>
                        <td>
                            @if ($battery->status === 'in_use' && $battery->currentRider)
                                {{ $battery->currentRider->name }} <br>
                                <small>{{ $battery->currentRider->phone }}</small>
                            @else
                                <em>—</em>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <a href="{{ route('admin.batteries.history', $battery->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                                <a href="{{ route('admin.batteries.edit', $battery) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.batteries.destroy', $battery) }}" method="POST" onsubmit="return confirm('Delete battery?')">
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
    {{ $batteries->links() }}
</div>
@endsection
