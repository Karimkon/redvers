@extends('admin.layouts.app')

@section('title', 'Add Motorcycle Unit')

@section('content')
<div class="container">
    <h4 class="mb-4">Add New Motorcycle Unit</h4>

    <form action="{{ route('admin.motorcycle-units.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Motorcycle Plan</label>
            <select name="motorcycle_id" class="form-control" required>
                <option value="">-- Select Type --</option>
                @foreach($motorcycles as $motorcycle)
                    <option value="{{ $motorcycle->id }}">{{ ucfirst($motorcycle->type) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Number Plate</label>
            <input type="text" name="number_plate" class="form-control" required placeholder="Enter number plate">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="available">Available</option>
                <option value="assigned">Assigned</option>
                <option value="damaged">Damaged</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Add Unit</button>
    </form>
</div>
@endsection
