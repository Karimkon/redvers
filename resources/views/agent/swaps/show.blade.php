@extends('agent.layouts.app')

@section('title', 'Swap Details')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Swap Details</h2>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Rider:</strong> {{ $swap->riderUser->name ?? 'N/A' }}</p>
            <p><strong>Battery Issued:</strong>
                {{ $swap->batterySwap->battery->serial_number ?? 'N/A' }}
            </p>
            <p><strong>Station:</strong> {{ $swap->station->name ?? 'N/A' }}</p>
            <p><strong>Swapped At:</strong> {{ $swap->swapped_at }}</p>
            <p><strong>Battery % Difference:</strong> {{ $swap->percentage_difference }}%</p>
            <p><strong>Payable Amount:</strong> UGX {{ number_format($swap->payable_amount) }}</p>
            <p><strong>Payment Method:</strong> {{ strtoupper($swap->payment_method ?? 'N/A') }}</p>
        </div>
    </div>

    <a href="{{ route('agent.swaps.index') }}" class="btn btn-secondary">← Back to Swaps</a>
</div>
@endsection
