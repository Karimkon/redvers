@extends('admin.layouts.app')

@section('title', 'Rider Details')

@section('content')
<h2 class="mb-4">Rider Details</h2>

<div class="card shadow-sm border-0">
    <div class="card-body row">

        {{-- Profile Photo --}}
        <div class="col-md-4 text-center">
            @if($rider->profile_photo)
                <img src="{{ asset($rider->profile_photo) }}"
                     alt="Profile Picture"
                     class="img-thumbnail rounded-circle shadow mb-3"
                     style="width: 180px; height: 180px; object-fit: cover;">
            @else
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width: 180px; height: 180px;">
                    <span>No Photo</span>
                </div>
            @endif
            <h5 class="text-primary mt-2">{{ $rider->name }}</h5>
        </div>

        {{-- Rider Info & IDs --}}
        <div class="col-md-8">
            <p><strong><i class="bi bi-phone me-1"></i> Phone:</strong> {{ $rider->phone }}</p>
            <p><strong><i class="bi bi-envelope me-1"></i> Email:</strong> {{ $rider->email ?? 'N/A' }}</p>
            <p><strong><i class="bi bi-person-vcard me-1"></i> NIN Number:</strong> {{ $rider->nin_number ?? 'Not Provided' }}</p>
            <p><strong><i class="bi bi-calendar-check me-1"></i> Registered:</strong> {{ $rider->created_at->format('d M Y, H:i A') }}</p>

            <div class="row mt-4">
                {{-- National ID Front --}}
                <div class="col-md-6 text-center">
                    <h6 class="fw-bold text-muted">National ID Front:</h6>
                    @if($rider->id_front)
                        <img src="{{ asset($rider->id_front) }}"
                             alt="ID Front"
                             class="img-fluid rounded shadow-sm border"
                             style="max-width: 100%; height: 240px; object-fit: cover;">
                    @else
                        <p class="text-muted fst-italic">Not uploaded</p>
                    @endif
                </div>

                {{-- National ID Back --}}
                <div class="col-md-6 text-center">
                    <h6 class="fw-bold text-muted">National ID Back:</h6>
                    @if($rider->id_back)
                        <img src="{{ asset($rider->id_back) }}"
                             alt="ID Back"
                             class="img-fluid rounded shadow-sm border"
                             style="max-width: 100%; height: 240px; object-fit: cover;">
                    @else
                        <p class="text-muted fst-italic">Not uploaded</p>
                    @endif
                </div>
            </div>

        </div> {{-- end col-md-8 --}}
    </div> {{-- end card-body --}}
</div> {{-- end card --}}
@endsection
