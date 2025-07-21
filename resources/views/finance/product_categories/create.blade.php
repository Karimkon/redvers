@extends('finance.layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="container">
    <h4 class="mb-4">➕ Add Product Category</h4>

    <form action="{{ route('finance.product_categories.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="text-end">
            <button class="btn btn-success">Save</button>
        </div>
    </form>
</div>
@endsection
