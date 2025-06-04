@extends('finance.layouts.app')

@section('title', 'Payment Details')

@section('content')
    <h2>Payment Details</h2>

    <ul class="list-group">
        <li class="list-group-item"><strong>Rider:</strong> {{ $payment->swap->rider->name ?? 'Unknown' }}</li>
        <li class="list-group-item"><strong>Email:</strong> {{ $payment->swap->rider->email ?? '-' }}</li>
        <li class="list-group-item"><strong>Phone:</strong> {{ $payment->swap->rider->phone ?? '-' }}</li>
        <li class="list-group-item"><strong>Amount:</strong> {{ number_format($payment->amount, 0) }} UGX</li>
        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($payment->status) }}</li>
        <li class="list-group-item"><strong>Method:</strong> {{ ucfirst($payment->method) }}</li>
        <li class="list-group-item"><strong>Reference:</strong> {{ $payment->reference }}</li>
        <li class="list-group-item"><strong>Initiated By:</strong> {{ ucfirst($payment->initiated_by) }}</li>
    </ul>

    <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary mt-3">Back to Payments</a>
@endsection
