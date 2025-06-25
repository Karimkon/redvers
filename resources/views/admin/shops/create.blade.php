@extends('admin.layouts.app')
@section('title', 'Create Shop')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Create Shop</h4>
    <form method="POST" action="{{ route('admin.shops.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input name="location" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input name="contact_number" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Assign User (Optional)</label>
            <select name="user_id" class="form-select">
                <option value="">-- None --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email ?? $user->phone }})</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Create</button>
    </form>
</div>
@endsection
