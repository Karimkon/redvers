@extends('admin.layouts.app')

@section('title', 'Create Station')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <h3 class="fw-bold text-primary mb-4">
        <i class="bi bi-geo-alt-fill me-2"></i> Create New Station
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

    <form method="POST" action="{{ route('admin.stations.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Station Name</label>
            <input type="text" name="name" class="form-control shadow-sm" required>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control shadow-sm" required>
            </div>
            <div class="col-md-6">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control shadow-sm" required>
            </div>
        </div>

        <div class="mt-4 mb-3">
            <label class="form-label">Select Station Location on Map</label>
            <div id="map" style="height: 350px;" class="rounded shadow-sm border"></div>
        </div>

        <button type="submit" class="btn btn-success shadow-sm">
            <i class="bi bi-check-circle me-1"></i> Save Station
        </button>
    </form>
</div>

{{-- Leaflet Styles & Script --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLocation = [0.3476, 32.5825]; // Kampala
        const map = L.map('map').setView(defaultLocation, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

        // Update input fields on drag
        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(7);
            document.getElementById('longitude').value = position.lng.toFixed(7);
        });

        // Set default values
        document.getElementById('latitude').value = defaultLocation[0];
        document.getElementById('longitude').value = defaultLocation[1];
    });
</script>
@endsection
