@extends('admin.layouts.app')

@section('title', 'Edit Station')

@section('content')
<h2>Edit Station</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.stations.update', $station) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Station Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $station->name) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Latitude</label>
        <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude', $station->latitude) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Longitude</label>
        <input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude', $station->longitude) }}" required>
    </div>

    <div id="map" style="height: 300px;" class="mb-3"></div>

    <button class="btn btn-primary">Update Station</button>
    <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary">Cancel</a>
</form>

<script>
    function initMap() {
        const initialPosition = {
            lat: parseFloat("{{ $station->latitude }}"),
            lng: parseFloat("{{ $station->longitude }}")
        };

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: initialPosition,
        });

        const marker = new google.maps.Marker({
            position: initialPosition,
            map: map,
            draggable: true,
        });

        marker.addListener('dragend', function(evt){
        const lat = parseFloat(evt.latLng.lat()).toFixed(7);
        const lng = parseFloat(evt.latLng.lng()).toFixed(7);

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        console.log("Latitude set to:", lat);
        console.log("Longitude set to:", lng);
    });

    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
@endsection
