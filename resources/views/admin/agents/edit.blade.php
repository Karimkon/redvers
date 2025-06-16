@extends('admin.layouts.app')

@section('title', 'Edit Agent')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-warning mb-4">
                        <i class="bi bi-pencil-square me-2"></i> Edit Agent Details
                    </h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.agents.update', $agent) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">👤 Full Name</label>
                            <input type="text" name="name" class="form-control shadow-sm" value="{{ old('name', $agent->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📞 Phone Number</label>
                            <input type="text" name="phone" class="form-control shadow-sm" value="{{ old('phone', $agent->phone) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📧 Email Address</label>
                            <input type="email" name="email" class="form-control shadow-sm" value="{{ old('email', $agent->email) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🔑 New Password <small class="text-muted">(optional)</small></label>
                                <input type="password" name="password" class="form-control shadow-sm">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">🔒 Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control shadow-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">📍 Assigned Station</label>
                            <select name="station_id" class="form-select shadow-sm" required>
                                <option value="">-- Select Station --</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}" {{ $agent->station_id == $station->id ? 'selected' : '' }}>
                                        {{ $station->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-warning shadow-sm">
                                <i class="bi bi-check2-square me-1"></i> Update Agent
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
