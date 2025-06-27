{{-- resources/views/admin/batteries/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Batteries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Batteries</h2>
    <a href="{{ route('admin.batteries.create') }}" class="btn btn-success">Add New Battery</a>
</div>

{{-- Search and Filter Form --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.batteries.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search Serial / Rider Name / Rider Phone</label>
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Enter serial number">
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter by Station</label>
                <select name="station_id" class="form-select">
                    <option value="">All Stations</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}" {{ $stationId == $station->id ? 'selected' : '' }}>
                            {{ $station->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('admin.batteries.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Batteries Table --}}
<div class="card">
    <div class="card-body">
        @if($batteries->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Current Station</th>
                            <th>Assigned Rider</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batteries as $battery)
                            <tr>
                                <td>
                                    <strong>{{ $battery->serial_number }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $battery->status == 'in_use' ? 'success' : ($battery->status == 'charging' ? 'warning' : ($battery->status == 'damaged' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $battery->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($battery->currentStation)
                                        <span class="text-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $battery->currentStation->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-question-circle me-1"></i>
                                            No Station
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Safe rider display with multiple fallback options --}}
                                    @if($battery->currentRider)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user text-success me-2"></i>
                                            <div>
                                                <div class="fw-bold">{{ $battery->currentRider->name }}</div>
                                                @if($battery->currentRider->phone)
                                                    <small class="text-muted">{{ $battery->currentRider->phone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-user-slash me-1"></i>
                                            Unassigned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.batteries.edit', $battery) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.batteries.history', $battery) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-history"></i> History
                                        </a>
                                        <form action="{{ route('admin.batteries.destroy', $battery) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this battery?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{ $batteries->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </ul>
                </nav>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-battery-empty fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No batteries found</h5>
                <p class="text-muted">
                    @if($search || $stationId)
                        Try adjusting your search criteria or 
                        <a href="{{ route('admin.batteries.index') }}">clear filters</a>
                    @else
                        <a href="{{ route('admin.batteries.create') }}">Create your first battery</a>
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection