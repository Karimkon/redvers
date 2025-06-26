@extends('admin.layouts.app')

@section('title', 'Edit Part')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Edit Part</h4>

    <form method="POST" action="{{ route('admin.parts.update', $part) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Shop</label>
                <select name="shop_id" class="form-select" required>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ $shop->id == $part->shop_id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $part->name }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $part->brand }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Buying Price</label>
                <input type="number" name="cost_price" class="form-control" value="{{ $part->cost_price }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Selling Price</label>
                <input type="number" name="price" class="form-control" value="{{ $part->price }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ $part->stock }}" required>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
