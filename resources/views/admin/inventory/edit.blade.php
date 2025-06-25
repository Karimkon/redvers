@extends('admin.layouts.app')

@section('title', 'Edit Inventory Operator')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Edit Operator: {{ $inventory->name }}</h4>

    <form method="POST" action="{{ route('admin.inventory.update', $inventory) }}">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input name="name" class="form-control" required value="{{ old('name', $inventory->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" required value="{{ old('phone', $inventory->phone) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email (optional)</label>
            <input name="email" class="form-control" value="{{ old('email', $inventory->email) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Shop</label>
            <select name="shop_id" class="form-select" required>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}" {{ $inventory->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password (optional)</label>
            <input name="password" type="password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input name="password_confirmation" type="password" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
