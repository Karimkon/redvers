@extends('admin.layouts.app')

@section('title', 'All Parts')

@section('content')
<div class="container-fluid px-3 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-primary">All Spare Parts</h4>
        <a href="{{ route('admin.parts.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Add New Part
        </a>
    </div>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Filter by Shop</label>
            <select name="shop_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Shops --</option>
                @foreach($shops as $shop)
                    <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                        {{ $shop->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Shop</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Stock</th>
                    <th>Buy (UGX)</th>
                    <th>Sell (UGX)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parts as $part)
                    <tr>
                        <td><span class="badge bg-dark">{{ $part->shop->name }}</span></td>
                        <td>{{ $part->name }}</td>
                        <td>{{ $part->brand }}</td>
                        <td>{{ $part->stock }}</td>
                        <td>{{ number_format($part->cost_price) }}</td>
                        <td>{{ number_format($part->price) }}</td>
                        <td>
                            <a href="{{ route('admin.parts.edit', $part) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('admin.parts.destroy', $part) }}"
                                  class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-muted text-center">No parts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $parts->withQueryString()->links() }}
    </div>
</div>
@endsection
