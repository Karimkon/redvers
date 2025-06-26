@extends('inventory.layouts.app')

@section('title', 'Part Details')

@section('content')
<div class="container">
    <!-- 🧭 Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-eye me-2"></i> Part Details
        </h4>
        <a href="{{ route('inventory.parts.index') }}" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-1"></i> Back to Parts
        </a>
    </div>

    <!-- 📦 Part Info Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">
                <span class="text-primary">{{ $part->name }}</span>
                <span class="badge bg-light text-dark ms-2">{{ $part->brand ?? 'No Brand' }}</span>
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="fw-semibold text-muted mb-1">Stock Available</div>
                        <div class="fs-4">{{ $part->stock }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="fw-semibold text-muted mb-1">Selling Price</div>
                        <div class="fs-4 text-success">UGX {{ number_format($part->price) }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="fw-semibold text-muted mb-1">Buying Price</div>
                        <div class="fs-5 text-danger">UGX {{ number_format($part->cost_price) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
