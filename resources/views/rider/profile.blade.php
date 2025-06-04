@extends('rider.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container">
    <h2 class="mb-4">My Profile</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $rider->profile_photo_url ?? asset('images/default-avatar.png') }}"
                 class="rounded-circle me-4" width="80" height="80" alt="Profile Picture" style="object-fit: cover;">

            <div>
                <h5 class="mb-1">{{ $rider->name }}</h5>
                <p class="mb-1"><strong>Email:</strong> {{ $rider->email }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $rider->phone }}</p>
                <p class="mb-1"><strong>Role:</strong> {{ ucfirst($rider->role) }}</p>
                <p class="mb-0"><strong>Station:</strong> {{ $rider->station->name ?? '—' }}</p>
            </div>
        </div>
    </div>

    <p class="text-muted mt-3">Note: Editing profile is currently disabled.</p>
</div>
@endsection
