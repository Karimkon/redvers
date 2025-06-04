@extends('admin.layouts.app')

@section('title', 'Edit Rider')

@section('content')
<h2 class="mb-4">Edit Rider</h2>

<form method="POST" action="{{ route('admin.riders.update', $rider) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $rider->name) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $rider->phone) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email (optional)</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $rider->email) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="nin_number" class="form-label">NIN Number</label>
            <input type="text" class="form-control" id="nin_number" name="nin_number" value="{{ old('nin_number', $rider->nin_number) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="profile_photo" class="form-label">Profile Picture</label>
            <input type="file" class="form-control" id="profile_photo" name="profile_photo">
            @if($rider->profile_photo)
                <img src="{{ asset('storage/' . $rider->profile_photo) }}" class="img-thumbnail mt-2" style="height: 120px;">
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label for="id_front" class="form-label">National ID Front</label>
            <input type="file" class="form-control" id="id_front" name="id_front">
            @if($rider->id_front)
                <img src="{{ asset('storage/' . $rider->id_front) }}" class="img-thumbnail mt-2" style="height: 120px;">
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label for="id_back" class="form-label">National ID Back</label>
            <input type="file" class="form-control" id="id_back" name="id_back">
            @if($rider->id_back)
                <img src="{{ asset('storage/' . $rider->id_back) }}" class="img-thumbnail mt-2" style="height: 120px;">
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">New Password (leave blank to keep)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success">Update Rider</button>
        <a href="{{ route('admin.riders.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection
