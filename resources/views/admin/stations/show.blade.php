@extends('admin.layouts.app')

@section('title', 'View Station')

@section('content')
<h2>Station Details</h2>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $station->name }}</h5>
        <p class="card-text"><strong>Latitude:</strong> {{ $station->latitude }}</p>
        <p class="card-text"><strong>Longitude:</strong> {{ $station->longitude }}</p>
        <div id="map" style="height: 300px;"></div>
    </div>
</div>

<a href="{{ route('admin.stations.index') }}" class="btn btn-secondary mt-3">Back to Stations</a>

<script>
    function initMap() {
        const stationLocation = {
            lat: parseFloat("{{ $station->latitude }}"),
            lng: parseFloat("{{ $station->longitude }}")
        };

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14,
            center: stationLocation,
        });

        new google.maps.Marker({
            position: stationLocation,
            map: map,
            title: "{{ $station->name }}"
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
@endsection
