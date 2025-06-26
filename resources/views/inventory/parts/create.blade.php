<!-- resources/views/inventory/parts/create.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Add New Part')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Add New Part</h4>

    <form method="POST" action="{{ route('inventory.parts.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Part Number</label>
                <input type="text" name="part_number" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control">
            </div>
             <div class="col-md-6">
                <label class="form-label">Buying Price (UGX)</label>
                <input type="number" name="cost_price" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Selling Price (UGX)</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Initial Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Save Part</button>
            <a href="{{ route('inventory.parts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection