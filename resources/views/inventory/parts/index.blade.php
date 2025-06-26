<!-- resources/views/inventory/parts/index.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Parts Inventory')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">My Spare Parts</h4>
        <a href="{{ route('inventory.parts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Part
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Part #</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Stock</th>
                    <th>Cost / Buying Price (UGX)</th>
                    <th>Price (UGX)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parts as $part)
                    <tr>
                        <td>{{ $part->name }}</td>
                        <td>{{ $part->part_number }}</td>
                        <td>{{ $part->category }}</td>
                        <td>{{ $part->brand }}</td>
                        <td>{{ $part->stock }}</td>
                        <td>{{ number_format($part->cost_price) }}</td>
                        <td>{{ number_format($part->price) }}</td>
                        <td>
                            <a href="{{ route('inventory.parts.edit', $part) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('inventory.parts.destroy', $part) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this part?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted text-center">No parts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $parts->links() }}
</div>
@endsection