@extends('admin.layouts.app')

@section('title', 'Create Shop')

@section('content')
<div class="container py-4">
    <!-- 🏷️ Page Title -->
    <div class="mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-shop me-2"></i> Create New Spare Shop
        </h4>
        <p class="text-muted mb-0">Fill in the details below to register a new shop in the system.</p>
    </div>

    <!-- 📋 Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.shops.store') }}">
                @csrf

                <div class="row g-3">
                    <!-- 🔤 Name -->
                    <div class="col-md-6">
                        <label class="form-label">Shop Name</label>
                        <input name="name" class="form-control" placeholder="e.g. Kisaasi Spares" required>
                    </div>

                    <!-- 📍 Location -->
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input name="location" class="form-control" placeholder="e.g. Kampala - Kisaasi">
                    </div>

                    <!-- 📞 Contact -->
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input name="contact_number" class="form-control" placeholder="e.g. +256700000000">
                    </div>

                    <!-- 👤 Optional User Assignment -->
                    <div class="col-md-6">
                        <label class="form-label">Assign User (Optional)</label>
                        <select name="user_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email ?? $user->phone }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">This user will be the default operator of this shop.</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Create Shop
                    </button>
                    <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
