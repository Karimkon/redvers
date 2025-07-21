@extends('finance.layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Add New Product</h4>

    <form method="POST" action="{{ route('finance.products.store') }}">
        @csrf
        <div class="card shadow-sm p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-6">
                    <label class="form-label">Unit Cost (UGX)</label>
                    <input type="number" name="unit_cost" class="form-control" required step="0.01" value="{{ old('unit_cost') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Description (optional)</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('finance.products.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </div>
    </form>
</div>
@endsection
