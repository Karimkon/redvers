@extends('agent.layouts.app')

@section('title', 'Confirm Promotion Payment')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 📝 Page Title --}}
    <h4 class="fw-bold text-primary mb-4">
        <i class="bi bi-cash-stack me-2"></i> Confirm Unlimited Swap Promotion
    </h4>

    {{-- 💳 Payment Confirmation Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="card-title mb-2">
                Rider: <span class="text-dark">{{ $rider->name }}</span> 
                <small class="text-muted">({{ $rider->phone }})</small>
            </h5>

            <p class="mb-4">Promotion Fee: <strong>UGX {{ number_format($amount) }}</strong></p>

            <form method="POST" action="{{ route('agent.promotions.pay.pesapal') }}">
                @csrf
                <input type="hidden" name="rider_id" value="{{ $rider->id }}">

                {{-- 📱 Payment Method --}}
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="pesapal">Pesapal</option>
                        {{-- Optional: Add other gateways later --}}
                        {{-- <option value="mtn">MTN MoMo</option>
                        <option value="airtel">Airtel Money</option> --}}
                    </select>
                </div>

                {{-- 🎯 Action Buttons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-credit-card me-1"></i> Pay UGX {{ number_format($amount) }}
                    </button>
                    <a href="{{ route('agent.promotions.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
