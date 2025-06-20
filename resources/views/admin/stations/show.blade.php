@extends('admin.layouts.app')

@section('title', 'View Station')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <h3 class="fw-bold text-primary mb-4">
        <i class="bi bi-geo-alt me-2"></i> Station Details
    </h3>

    <div class="card shadow-sm border">
        <div class="card-body">
            <h5 class="card-title">{{ $station->name }}</h5>
            <p class="card-text mb-1"><strong>Latitude:</strong> {{ $station->latitude }}</p>
            <p class="card-text mb-3"><strong>Longitude:</strong> {{ $station->longitude }}</p>
            <div id="map" style="height: 350px;" class="rounded shadow-sm border"></div>
        </div>
    </div>

    <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary mt-4">
        <i class="bi bi-arrow-left me-1"></i> Back to Stations
    </a>
</div>

{{-- Leaflet Styles & Script --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = parseFloat("{{ $station->latitude ?? 0.3476 }}");
        const lng = parseFloat("{{ $station->longitude ?? 32.5825 }}");

        const map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng])
            .addTo(map)
            .bindPopup("{{ $station->name }}")
            .openPopup();
    });
</script>
@endsection
