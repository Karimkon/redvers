@extends('admin.layouts.app')

@section('title', 'Edit Motorcycle Unit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">🛠️ Edit Motorcycle Unit</h4>
        <a href="{{ route('admin.motorcycle-units.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Units
        </a>
    </div>

    <form action="{{ route('admin.motorcycle-units.update', $motorcycleUnit->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        {{-- Motorcycle Plan --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">🚲 Motorcycle Plan</label>
            <select name="motorcycle_id" class="form-select" required>
                <option disabled>Select a plan</option>
                @foreach($motorcycles as $motorcycle)
                    <option value="{{ $motorcycle->id }}" {{ $motorcycleUnit->motorcycle_id == $motorcycle->id ? 'selected' : '' }}>
                        {{ ucfirst($motorcycle->type) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Number Plate --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">🔢 Number Plate</label>
            <input type="text" name="number_plate" class="form-control" value="{{ $motorcycleUnit->number_plate }}" required>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">📦 Status</label>
            <select name="status" class="form-select">
                <option value="available" {{ $motorcycleUnit->status == 'available' ? 'selected' : '' }}>Available</option>
                <option value="assigned" {{ $motorcycleUnit->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="damaged" {{ $motorcycleUnit->status == 'damaged' ? 'selected' : '' }}>Damaged</option>
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Update Unit
            </button>
        </div>
    </form>
</div>
@endsection
