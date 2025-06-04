@extends('admin.layouts.app')

@section('title', 'Add Battery')

@section('content')
<h2>Register Battery</h2>

<form method="POST" action="{{ route('admin.batteries.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Serial Number</label>
        <input type="text" name="serial_number" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="in_stock">In Stock</option>
            <option value="in_use">In Use</option>
            <option value="charging">Charging</option>
            <option value="damaged">Damaged</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Assign to Station</label>
        <select name="current_station_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($stations as $station)
                <option value="{{ $station->id }}">{{ $station->name }}</option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary">Save</button>
</form>
@endsection
