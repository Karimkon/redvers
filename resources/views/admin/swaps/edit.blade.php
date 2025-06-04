@extends('admin.layouts.app')

@section('title', 'Edit Swap')

@section('content')
<h2>Edit Swap</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.swaps.update', $swap->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Rider</label>
        <select name="rider_id" class="form-select" required>
            @foreach($riders as $rider)
                <option value="{{ $rider->id }}" {{ $swap->rider_id == $rider->id ? 'selected' : '' }}>{{ $rider->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Station</label>
        <select name="station_id" class="form-select" required>
            @foreach($stations as $station)
                <option value="{{ $station->id }}" {{ $swap->station_id == $station->id ? 'selected' : '' }}>{{ $station->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Agent (optional)</label>
        <select name="agent_id" class="form-select">
            <option value="">None</option>
            @foreach($agents as $agent)
                <option value="{{ $agent->id }}" {{ $swap->agent_id == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Battery Percentage</label>
        <input type="number" name="percentage_difference" id="battery" class="form-control" min="0" max="100" value="{{ $swap->percentage_difference }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" id="payment_method" class="form-select">
            <option value="" {{ $swap->payment_method == null ? 'selected' : '' }}>None</option>
            <option value="mtn" {{ $swap->payment_method == 'mtn' ? 'selected' : '' }}>MTN</option>
            <option value="airtel" {{ $swap->payment_method == 'airtel' ? 'selected' : '' }}>Airtel</option>
        </select>
    </div>

    <button class="btn btn-primary">Update Swap</button>
</form>

<script>
    const batteryInput = document.getElementById('battery');
    const basePrice = {{ env('BASE_PRICE', 12000) }};
</script>
@endsection
