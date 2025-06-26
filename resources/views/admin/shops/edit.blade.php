@extends('admin.layouts.app')

@section('title', 'Edit Shop')

@section('content')
<div class="container py-4">
    <!-- 🔧 Header -->
    <div class="mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-pencil-square me-2"></i> Edit Shop Details
        </h4>
        <p class="text-muted mb-0">Update information for <strong>{{ $shop->name }}</strong>.</p>
    </div>

    <!-- 📝 Edit Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.shops.update', $shop) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- 🏷️ Shop Name -->
                    <div class="col-md-6">
                        <label class="form-label">Shop Name</label>
                        <input name="name" class="form-control" value="{{ old('name', $shop->name) }}" required>
                    </div>

                    <!-- 📍 Location -->
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input name="location" class="form-control" value="{{ old('location', $shop->location) }}">
                    </div>

                    <!-- 📞 Contact -->
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input name="contact_number" class="form-control" value="{{ old('contact_number', $shop->contact_number) }}">
                    </div>

                    <!-- 👤 Assign User -->
                    <div class="col-md-6">
                        <label class="form-label">Assign User (Optional)</label>
                        <select name="user_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $shop->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email ?? $user->phone }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Assign a system user to manage this shop.</small>
                    </div>
                </div>

                <!-- 🔘 Action Buttons -->
                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Shop
                    </button>
                    <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Shops
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
