{{-- resources/views/admin/batteries/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Battery')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Battery: {{ $battery->serial_number }}</h2>
                <a href="{{ route('admin.batteries.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Batteries
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6 class="alert-heading">Please correct the following errors:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Battery Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.batteries.update', $battery) }}">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Serial Number <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   name="serial_number" 
                                                   class="form-control @error('serial_number') is-invalid @enderror" 
                                                   value="{{ old('serial_number', $battery->serial_number) }}" 
                                                   required>
                                            @error('serial_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="in_stock" {{ old('status', $battery->status) == 'in_stock' ? 'selected' : '' }}>
                                                    📦 In Stock
                                                </option>
                                                <option value="in_use" {{ old('status', $battery->status) == 'in_use' ? 'selected' : '' }}>
                                                    🚴 In Use
                                                </option>
                                                <option value="charging" {{ old('status', $battery->status) == 'charging' ? 'selected' : '' }}>
                                                    🔌 Charging
                                                </option>
                                                <option value="damaged" {{ old('status', $battery->status) == 'damaged' ? 'selected' : '' }}>
                                                    ⚠️ Damaged
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Assign to Rider</label>
                                            <select name="current_rider_id" class="form-select @error('current_rider_id') is-invalid @enderror">
                                                <option value="">-- No Rider Assigned --</option>
                                                @foreach($riders as $rider)
                                                    <option value="{{ $rider->id }}" 
                                                            {{ old('current_rider_id', $battery->current_rider_id) == $rider->id ? 'selected' : '' }}>
                                                        {{ $rider->name }}
                                                        @if($rider->phone)
                                                            ({{ $rider->phone }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('current_rider_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Assigning to a new rider will automatically remove any other battery from that rider.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Current Station</label>
                                            <select name="current_station_id" class="form-select @error('current_station_id') is-invalid @enderror">
                                                <option value="">-- No Station --</option>
                                                @foreach($stations as $station)
                                                    <option value="{{ $station->id }}" 
                                                            {{ old('current_station_id', $battery->current_station_id) == $station->id ? 'selected' : '' }}>
                                                        {{ $station->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('current_station_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Update Battery
                                    </button>
                                    <a href="{{ route('admin.batteries.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Current Assignment</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Current Rider:</strong>
                                <div class="mt-1">
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
                                            No rider assigned
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Current Station:</strong>
                                <div class="mt-1">
                                    @if($battery->currentStation)
                                        <span class="text-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $battery->currentStation->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-question-circle me-1"></i>
                                            No station assigned
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Status:</strong>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $battery->status == 'in_use' ? 'success' : ($battery->status == 'charging' ? 'warning' : ($battery->status == 'damaged' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $battery->status)) }}
                                    </span>
                                </div>
                            </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.batteries.history', $battery) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-history me-1"></i> View History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-update status based on rider assignment
document.querySelector('select[name="current_rider_id"]').addEventListener('change', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    const currentStatus = statusSelect.value;
    
    if (this.value && currentStatus === 'in_stock') {
        // If assigning a rider and status is in_stock, suggest changing to in_use
        if (confirm('Would you like to change the status to "In Use" since you\'re assigning this battery to a rider?')) {
            statusSelect.value = 'in_use';
        }
    } else if (!this.value && currentStatus === 'in_use') {
        // If removing a rider and status is in_use, suggest changing to in_stock
        if (confirm('Would you like to change the status to "In Stock" since you\'re removing the rider assignment?')) {
            statusSelect.value = 'in_stock';
        }
    }
});
</script>
@endpush