@extends('admin.layouts.app')

@section('title', 'Add Part to Shop')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Add New Part</h4>

    <form method="POST" action="{{ route('admin.parts.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Shop</label>
                <select name="shop_id" class="form-select" required>
                    <option value="">-- Select Shop --</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Part Name</label>
                <input name="name" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Part Number</label>
                <input name="part_number" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input name="category" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input name="brand" class="form-control">
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
            <button class="btn btn-success">Save Part</button>
            <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
