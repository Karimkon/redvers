@extends('rider.layouts.app')

@section('title', 'Motorcycle Payment')

@section('content')
<div class="container my-4">
    <h4><i class="bi bi-wallet-fill me-2"></i> Motorcycle Payment</h4>

    <div class="card shadow-sm my-3">
        <div class="card-body">
            <p><strong>Daily Rate:</strong> UGX {{ number_format($purchase->daily_rate) }}</p>
            
            @if($overdueSummary['is_overdue'])
                <p><strong>Overdue Amount:</strong> UGX {{ number_format($overdueSummary['due_amount']) }}</p>
                <p><strong>Missed Days:</strong> {{ $overdueSummary['missed_days'] }}</p>
            @else
                <p class="text-success">No overdue payments 🎉</p>
            @endif

            <form action="{{ route('rider.daily-payment.pay') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount to Pay (UGX)</label>
                    <input type="number" class="form-control" name="amount" min="{{ $purchase->daily_rate }}" value="{{ $amount }}" required>
                </div>
                <button class="btn btn-success">
                    <i class="bi bi-credit-card me-2"></i> Pay via Pesapal
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
