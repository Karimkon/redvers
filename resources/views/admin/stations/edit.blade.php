@extends('admin.layouts.app')

@section('title', 'Edit Station')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <h3 class="fw-bold text-primary mb-4">
        <i class="bi bi-geo-alt-fill me-2"></i> Edit Station
    </h3>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.stations.update', $station) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Station Name</label>
            <input type="text" name="name" class="form-control shadow-sm" value="{{ old('name', $station->name) }}" required>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control shadow-sm"
                    value="{{ old('latitude', $station->latitude) }}" required>
            </div>
            <div class="col-md-6">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control shadow-sm"
                    value="{{ old('longitude', $station->longitude) }}" required>
            </div>
        </div>

        <div class="mt-4 mb-3">
            <label class="form-label">Update Station Location</label>
            <div id="map" style="height: 350px;" class="rounded shadow-sm border"></div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary shadow-sm">
                <i class="bi bi-save me-1"></i> Update Station
            </button>
            <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Cancel
            </a>
        </div>
    </form>
</div>

{{-- Leaflet JS & CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = parseFloat("{{ $station->latitude }}");
        const lng = parseFloat("{{ $station->longitude }}");
        const map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function (e) {
            const pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat.toFixed(7);
            document.getElementById('longitude').value = pos.lng.toFixed(7);
        });
    });
</script>
@endsection
