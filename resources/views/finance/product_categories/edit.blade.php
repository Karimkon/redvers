@extends('finance.layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="container">
    <h4 class="mb-4">✏️ Edit Product Category</h4>

    <form action="{{ route('finance.product_categories.update', $productCategory) }}" method="POST" class="card p-4 shadow-sm">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ $productCategory->name }}" required>
        </div>
        <div class="text-end">
            <button class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
