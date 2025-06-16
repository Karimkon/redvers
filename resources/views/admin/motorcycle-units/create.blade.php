@extends('admin.layouts.app')

@section('title', 'Add Motorcycle Unit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">➕ Add New Motorcycle Unit</h4>
        <a href="{{ route('admin.motorcycle-units.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Units
        </a>
    </div>

    <form action="{{ route('admin.motorcycle-units.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf

        {{-- Motorcycle Plan --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">🚲 Motorcycle Plan</label>
            <select name="motorcycle_id" class="form-select" required>
                <option disabled selected>-- Select Type --</option>
                @foreach($motorcycles as $motorcycle)
                    <option value="{{ $motorcycle->id }}">{{ ucfirst($motorcycle->type) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Number Plate --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">🔢 Number Plate</label>
            <input type="text" name="number_plate" class="form-control" required placeholder="Enter number plate">
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">📦 Status</label>
            <select name="status" class="form-select">
                <option value="available">Available</option>
                <option value="assigned">Assigned</option>
                <option value="damaged">Damaged</option>
            </select>
        </div>

        {{-- Submit --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add Unit
            </button>
        </div>
    </form>
</div>
@endsection
