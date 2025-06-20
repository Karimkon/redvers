@extends('agent.layouts.app')

@section('title', 'Swap Details')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 🔁 Page Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-battery-charging me-2 text-primary"></i> Swap Details
        </h4>
        <a href="{{ route('agent.swaps.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Swaps
        </a>
    </div>

    {{-- 📋 Swap Info Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="row gy-3">
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Rider</p>
                    <h6>{{ $swap->riderUser->name ?? 'N/A' }}</h6>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 text-muted">Battery Issued</p>
                    <h6>{{ $swap->batterySwap->battery->serial_number ?? 'N/A' }}</h6>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 text-muted">Station</p>
                    <h6>{{ $swap->station->name ?? 'N/A' }}</h6>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 text-muted">Swapped At</p>
                    <h6>{{ $swap->swapped_at->format('d M Y, H:i') }}</h6>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 text-muted">Battery % Difference</p>
                    <h6>{{ $swap->percentage_difference }}%</h6>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 text-muted">Payable Amount</p>
                    <h6>UGX {{ number_format($swap->payable_amount) }}</h6>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 text-muted">Payment Method</p>
                    <h6>{{ strtoupper($swap->payment_method ?? 'N/A') }}</h6>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 text-muted">Payment Status</p>
                    <h6>
                        @if($swap->payment && $swap->payment->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($swap->payment && $swap->payment->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @else
                            <span class="badge bg-danger">Not Paid</span>
                        @endif
                    </h6>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
