@extends('admin.layouts.app')

@section('title', 'Shops')

@section('content')
<div class="container-fluid px-3 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">All Shops</h4>
        <a href="{{ route('admin.shops.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Add Shop
        </a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Shop Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
           @foreach($shops as $shop)
            <tr>
                <td>{{ $shop->name }}</td>
                <td>
                    <a href="{{ route('admin.shops.analytics', $shop->id) }}" class="btn btn-sm btn-primary">View Analytics</a>
                    <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.shops.destroy', $shop->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this shop?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
