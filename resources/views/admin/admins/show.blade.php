@extends('admin.layouts.app')

@section('title', 'Admin User Details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm p-4 text-center">
                <img src="{{ $admin->profile_photo ? asset($admin->profile_photo) : asset('images/default-user.png') }}" class="rounded-circle mb-3" width="120" alt="Profile Photo">
                <h4 class="fw-bold">{{ $admin->name }}</h4>
                <p><strong>Phone:</strong> {{ $admin->phone }}</p>
                <p><strong>Email:</strong> {{ $admin->email ?? '-' }}</p>

                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning me-2">Edit</a>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
