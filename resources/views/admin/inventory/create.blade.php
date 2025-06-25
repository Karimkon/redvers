@extends('admin.layouts.app')

@section('title', 'Register Inventory Operator')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Register Inventory Operator</h4>

    <form method="POST" action="{{ route('admin.inventory.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" required value="{{ old('phone') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email (optional)</label>
            <input name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Shop</label>
            <select name="shop_id" class="form-select" required>
                <option value="">Select Shop</option>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
