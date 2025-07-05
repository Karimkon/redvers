@extends('admin.layouts.app')

@section('title', 'Edit Finance Staff')

@section('content')
<h2 class="mb-4">Edit Finance Staff</h2>

<form method="POST" action="{{ route('admin.finance.update', $finance) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Full Name</label>
            <input name="name" class="form-control" value="{{ old('name',$finance->name) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Phone Number</label>
            <input name="phone" class="form-control" value="{{ old('phone',$finance->phone) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Email (optional)</label>
            <input type="email" name="email" class="form-control" value="{{ old('email',$finance->email) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Profile Picture</label>
            <input type="file" name="profile_photo" class="form-control">
            @if($finance->profile_photo)
                <img src="{{ asset($finance->profile_photo) }}" class="img-thumbnail mt-2" style="height:120px;">
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">New Password (leave blank to keep)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-success">Update Staff</button>
        <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection
