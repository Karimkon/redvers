@extends('inventory.layouts.app')

@section('title', 'Parts Inventory')

@section('content')
<div class="container">
    <!-- 🔖 Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-tools me-2"></i> {{ Auth::user()->shop->name ?? 'My Shop' }} Inventory
        </h4>

    </div>

    <!-- ✅ Flash Message -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded-3">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 📋 Parts Table -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Part Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Buying Price (UGX)</th>
                        <th scope="col">Selling Price (UGX)</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                        <tr>
                            <td class="fw-semibold">{{ $part->name }}</td>
                            <td>{{ $part->brand ?? '-' }}</td>
                            <td>
                                @if($part->stock < 5)
                                    <span class="badge bg-danger">{{ $part->stock }}</span>
                                @elseif($part->stock < 10)
                                    <span class="badge bg-warning text-dark">{{ $part->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ $part->stock }}</span>
                                @endif
                            </td>
                            <td>UGX {{ number_format($part->cost_price) }}</td>
                            <td>UGX {{ number_format($part->price) }}</td>
                            <td>
                                <a href="{{ route('inventory.parts.show', $part) }}" class="btn btn-sm btn-outline-info rounded-pill">
                                    <i class="bi bi-eye-fill"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-info-circle me-1"></i> No parts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-4">
        {{ $parts->links() }}
    </div>
</div>
@endsection
