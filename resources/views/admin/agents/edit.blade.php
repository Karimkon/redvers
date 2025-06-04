@extends('admin.layouts.app')

@section('title', 'Edit Agent')

@section('content')
<h2>Edit Agent</h2>

<form method="POST" action="{{ route('admin.agents.update', $agent) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $agent->name) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $agent->phone) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $agent->email) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">New Password (optional)</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Station</label>
        <select name="station_id" class="form-control" required>
            <option value="">-- Select Station --</option>
            @foreach($stations as $station)
                <option value="{{ $station->id }}" {{ $agent->station_id == $station->id ? 'selected' : '' }}>
                    {{ $station->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary">Update Agent</button>
</form>
@endsection
