@extends('admin.layouts.app')

@section('title', 'View Agent')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-4">
                        <i class="bi bi-person-badge me-2"></i> Agent Profile
                    </h4>

                    <div class="mb-3">
                        <label class="text-muted small">Full Name</label>
                        <div class="fs-5 fw-semibold text-dark">{{ $agent->name }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Phone</label>
                        <div class="fs-6 text-dark">
                            <i class="bi bi-telephone me-1"></i> {{ $agent->phone }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <div class="fs-6 text-dark">
                            <i class="bi bi-envelope me-1"></i> {{ $agent->email ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Station</label>
                        <div class="fs-6 text-dark">
                            <i class="bi bi-geo-alt me-1"></i> {{ $agent->station->name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Created At</label>
                        <div class="text-dark">{{ $agent->created_at->format('d M Y, h:i A') }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small">Last Updated</label>
                        <div class="text-dark">{{ $agent->updated_at->format('d M Y, h:i A') }}</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-warning shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary shadow-sm">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
