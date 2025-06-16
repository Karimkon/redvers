@extends('agent.layouts.app')

@section('title', 'Confirm Promotion Payment')

@section('content')
<div class="container">
    <h2 class="mb-4">Confirm Unlimited Swap Promotion</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Rider: {{ $rider->name }} ({{ $rider->phone }})</h5>
            <p class="card-text">Promotion Fee: <strong>UGX {{ number_format($amount) }}</strong></p>

            <form method="POST" action="{{ route('agent.promotions.pay.pesapal') }}">
                @csrf
                <input type="hidden" name="rider_id" value="{{ $rider->id }}">

                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="pesapal">Pesapal</option>
                        {{-- optionally allow mtn/airtel --}}
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-credit-card"></i> Pay UGX {{ number_format($amount) }}
                </button>

                <a href="{{ route('agent.promotions.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
