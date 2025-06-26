@extends('admin.layouts.app')

@section('title', 'Shops')

@section('content')
<div class="container-fluid px-3 py-4">

    <!-- 🔖 Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-shop me-2"></i> Manage Spare Shops
        </h4>
        <a href="{{ route('admin.shops.create') }}" class="btn btn-success d-flex align-items-center gap-1">
            <i class="bi bi-plus-circle"></i> Add Shop
        </a>
    </div>

    <!-- 📋 Shops Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Shop Name</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shops as $shop)
                        <tr>
                            <td class="ps-4">
                                <i class="bi bi-building me-2 text-secondary"></i>
                                <strong>{{ $shop->name }}</strong>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.shops.analytics', $shop->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="bi bi-graph-up"></i> Analytics
                                </a>
                                <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.shops.destroy', $shop->id) }}" method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this shop?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">No shops found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
