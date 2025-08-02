@extends('rider.layouts.app')

@section('title', 'My Payments')

@section('content')
<h4 class="mb-4">📅 My Motorcycle Payments</h4>

@if(isset($message))
    <div class="alert alert-info">{{ $message }}</div>
@else
<div class="card mb-3">
    <div class="card-body">
        <p><strong>Motorcycle Type:</strong> {{ ucfirst($purchase->motorcycle->type) }}</p>
        <p><strong>Total Price:</strong> UGX {{ number_format($purchase->total_price) }}</p>
        <p><strong>Amount Paid:</strong> UGX {{ number_format($purchase->amount_paid) }}</p>
        <p><strong>Remaining Balance:</strong> UGX {{ number_format($purchase->remaining_balance) }}</p>
        <p><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($purchase->status) }}</span></p>
        <p><strong>Next Due Date:</strong> {{ $nextDueDate }}</p>
        <p><strong>Payments Made:</strong> {{ $actualPayments }}</p>
        <p><strong>Missed Payments:</strong> <span class="text-danger fw-bold">{{ $missedPayments }}</span></p>

        <div class="progress mt-3">
            <div class="progress-bar" role="progressbar" style="width: {{ $completion }}%;" aria-valuenow="{{ $completion }}" aria-valuemin="0" aria-valuemax="100">
                {{ number_format($completion, 1) }}%
            </div>
        </div>

        <a href="{{ route('rider.payments.download') }}" class="btn btn-sm btn-outline-dark mt-3">
            <i class="bi bi-download"></i> Download PDF Summary
        </a>

        {{-- ✅ Pay Now Button --}}
        <a href="{{ route('rider.daily-payment.create') }}" class="btn btn-sm btn-success mt-3 ms-2">
            <i class="bi bi-credit-card"></i> Pay Now
        </a>

    </div>
</div>
@endif
@endsection
