@extends('finance.layouts.app')
@section('title', 'Products')

@section('content')
<div class="container-fluid py-4">
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <h4 class="fw-bold text-primary mb-4">Product List</h4>

    <a href="{{ route('finance.products.create') }}" class="btn btn-success mb-3">+ Add Product</a>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit Cost (UGX)</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? 'N/A' }}</td>
                        <td>{{ number_format($product->unit_cost) }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <a href="{{ route('finance.products.show', $product) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('finance.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('finance.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
