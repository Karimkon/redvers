@extends('admin.layouts.app')

@section('title', 'Finance Staff Details')

@section('content')
<h2 class="mb-4">Finance Staff Details</h2>

<div class="card shadow-sm border-0">
    <div class="card-body row">

        {{-- Photo --}}
        <div class="col-md-4 text-center">
            @if($finance->profile_photo)
                <img src="{{ asset($finance->profile_photo) }}" class="img-thumbnail rounded-circle shadow mb-3"
                     style="width:180px;height:180px;object-fit:cover;">
            @else
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:180px;height:180px;">
                    <span>No Photo</span>
                </div>
            @endif
            <h5 class="text-primary mt-2">{{ $finance->name }}</h5>
        </div>

        {{-- Info --}}
        <div class="col-md-8">
            <p><strong><i class="bi bi-phone me-1"></i> Phone:</strong> {{ $finance->phone }}</p>
            <p><strong><i class="bi bi-envelope me-1"></i> Email:</strong> {{ $finance->email ?? 'N/A' }}</p>
            <p><strong><i class="bi bi-calendar-check me-1"></i> Added:</strong> {{ $finance->created_at->format('d M Y, H:i A') }}</p>
        </div>
    </div>
</div>
@endsection
