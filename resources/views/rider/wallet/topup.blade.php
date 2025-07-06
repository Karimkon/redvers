@extends('rider.layouts.app')

@section('title', 'Top Up Wallet')

@section('content')
<div class="container py-4">

    {{-- 🔹 Page Header --}}
    <h3 class="mb-4">
        <i class="bi bi-wallet2 me-2 text-primary"></i> Top Up Wallet
    </h3>

    {{-- 🔹 Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🔹 Top-Up Form --}}
    <form action="{{ route('rider.wallet.topup.initiate') }}" method="POST" class="card shadow-sm p-4 border-success">
        @csrf

        {{-- Amount --}}
        <div class="mb-4">
            <label for="amount" class="form-label fw-semibold">
                <i class="bi bi-cash-stack me-1 text-success"></i> Amount (UGX)
            </label>
            <input type="number" min="1000" step="100" name="amount" id="amount" class="form-control form-control-lg" placeholder="Enter amount e.g. 5000" required>
            <small class="form-text text-muted">Minimum top-up: UGX 1,000</small>
        </div>

        {{-- Payment Method --}}
        <div class="mb-4">
            <label for="payment_method" class="form-label fw-semibold">
                <i class="bi bi-phone me-1 text-primary"></i> Payment Method
            </label>
            <select name="payment_method" id="payment_method" class="form-select form-select-lg" required>
                <option value="">Choose a payment method</option>
                <option value="mtn">MTN Mobile Money</option>
                <option value="airtel">Airtel Money</option>
                <option value="pesapal">Pesapal</option>
            </select>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-circle me-1"></i> Proceed to Pay
            </button>
            <a href="{{ route('rider.wallet.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection