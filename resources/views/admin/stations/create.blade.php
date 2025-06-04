@extends('admin.layouts.app')

@section('title', 'Create Station')

@section('content')
<h2>Create Station</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.stations.store') }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Station Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="latitude" class="form-label">Latitude</label>
        <input type="text" name="latitude" id="latitude" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="longitude" class="form-label">Longitude</label>
        <input type="text" name="longitude" id="longitude" class="form-control" required>
    </div>

    <div id="map" style="height: 300px;" class="mb-3"></div>

    <button class="btn btn-success">Save Station</button>
</form>

<script>
    function initMap() {
        const defaultCenter = { lat: 0.3476, lng: 32.5825 }; // Kampala
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: defaultCenter,
        });

        const marker = new google.maps.Marker({
            position: defaultCenter,
            map: map,
            draggable: true,
        });

        google.maps.event.addListener(marker, 'dragend', function(evt){
            document.getElementById('latitude').value = evt.latLng.lat().toFixed(7);
            document.getElementById('longitude').value = evt.latLng.lng().toFixed(7);
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
@endsection
