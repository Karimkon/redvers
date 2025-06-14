@extends('admin.layouts.app')

@section('title', 'Edit Motorcycle Unit')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Motorcycle Unit</h4>

    <form action="{{ route('admin.motorcycle-units.update', $motorcycleUnit->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Motorcycle Plan</label>
            <select name="motorcycle_id" class="form-control" required>
                @foreach($motorcycles as $motorcycle)
                    <option value="{{ $motorcycle->id }}" {{ $motorcycleUnit->motorcycle_id == $motorcycle->id ? 'selected' : '' }}>
                        {{ ucfirst($motorcycle->type) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Number Plate</label>
            <input type="text" name="number_plate" class="form-control" value="{{ $motorcycleUnit->number_plate }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="available" {{ $motorcycleUnit->status == 'available' ? 'selected' : '' }}>Available</option>
                <option value="assigned" {{ $motorcycleUnit->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="damaged" {{ $motorcycleUnit->status == 'damaged' ? 'selected' : '' }}>Damaged</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Unit</button>
    </form>
</div>
@endsection
