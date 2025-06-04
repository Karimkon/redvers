{{-- resources/views/admin/batteries/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Battery')

@section('content')
<h2>Edit Battery</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.batteries.update', $battery) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Serial Number</label>
        <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $battery->serial_number) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="in_stock" {{ $battery->status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
            <option value="in_use" {{ $battery->status == 'in_use' ? 'selected' : '' }}>In Use</option>
            <option value="charging" {{ $battery->status == 'charging' ? 'selected' : '' }}>Charging</option>
            <option value="damaged" {{ $battery->status == 'damaged' ? 'selected' : '' }}>Damaged</option>

        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Current Station</label>
        <select name="current_station_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($stations as $station)
                <option value="{{ $station->id }}" {{ $battery->current_station_id == $station->id ? 'selected' : '' }}>
                    {{ $station->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-success">Update Battery</button>
    <a href="{{ route('admin.batteries.index') }}" class="btn btn-secondary ms-2">Cancel</a>
</form>
@endsection
