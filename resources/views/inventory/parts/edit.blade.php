<!-- resources/views/inventory/parts/edit.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Edit Part')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Edit Part</h4>

    <form method="POST" action="{{ route('inventory.parts.update', $part) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $part->name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Part Number</label>
                <input type="text" name="part_number" class="form-control" value="{{ $part->part_number }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="{{ $part->category }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $part->brand }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Price (UGX)</label>
                <input type="number" name="price" class="form-control" value="{{ $part->price }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ $part->stock }}" required>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Part</button>
            <a href="{{ route('inventory.parts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
