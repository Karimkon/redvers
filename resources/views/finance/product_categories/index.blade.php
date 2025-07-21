@extends('finance.layouts.app')

@section('title', 'Product Categories')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📦 Product Categories</h4>
        <a href="{{ route('finance.product_categories.create') }}" class="btn btn-primary">Add Category</a>
    </div>

    <div class="card shadow-sm">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name }}</td>
                        <td>
                            <a href="{{ route('finance.product_categories.edit', $cat) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('finance.product_categories.destroy', $cat) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Delete this category?')" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
