@extends('admin.layouts.app')
@section('title', 'Edit Shop')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Edit Shop</h4>
    <form method="POST" action="{{ route('admin.shops.update', $shop) }}">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" required value="{{ old('name', $shop->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input name="location" class="form-control" value="{{ old('location', $shop->location) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input name="contact_number" class="form-control" value="{{ old('contact_number', $shop->contact_number) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Assign User (Optional)</label>
            <select name="user_id" class="form-select">
                <option value="">-- None --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $shop->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email ?? $user->phone }})
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
