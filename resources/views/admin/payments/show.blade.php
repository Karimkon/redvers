@extends('admin.layouts.app')

@section('title', 'View Payment Details')

@section('content')
<div class="container-fluid px-3 px-md-4">
    {{-- Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-receipt-cutoff me-2"></i> Payment Summary
        </h3>
    </div>

    {{-- Payment Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 fw-semibold">Rider Name</dt>
                <dd class="col-sm-9">{{ $payment->swap->rider->name ?? 'Unknown' }}</dd>

                <dt class="col-sm-3 fw-semibold">Phone</dt>
                <dd class="col-sm-9">{{ $payment->swap->rider->phone ?? 'N/A' }}</dd>

                <dt class="col-sm-3 fw-semibold">Email</dt>
                <dd class="col-sm-9">{{ $payment->swap->rider->email ?? 'N/A' }}</dd>

                <dt class="col-sm-3 fw-semibold">Amount Paid</dt>
                <dd class="col-sm-9 text-primary fw-bold">{{ number_format($payment->amount, 0) }} UGX</dd>

                <dt class="col-sm-3 fw-semibold">Payment Method</dt>
                <dd class="col-sm-9"><span class="badge bg-secondary">{{ ucfirst($payment->method) }}</span></dd>

                <dt class="col-sm-3 fw-semibold">Status</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-{{ 
                        $payment->status == 'completed' ? 'success' : 
                        ($payment->status == 'pending' ? 'warning text-dark' : 'danger') 
                    }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </dd>

                <dt class="col-sm-3 fw-semibold">Reference ID</dt>
                <dd class="col-sm-9"><code>{{ $payment->reference }}</code></dd>

                <dt class="col-sm-3 fw-semibold">Initiated By</dt>
                <dd class="col-sm-9">{{ ucfirst($payment->initiated_by ?? 'admin') }}</dd>

                <dt class="col-sm-3 fw-semibold">Created At</dt>
                <dd class="col-sm-9">{{ $payment->created_at->format('d M Y H:i') }}</dd>

                <dt class="col-sm-3 fw-semibold">Related Swap</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('admin.swaps.show', $payment->swap_id) }}" class="text-decoration-underline">
                        View Swap #{{ $payment->swap_id }}
                    </a>
                </dd>

                <dt class="col-sm-3 fw-semibold">Battery Returned</dt>
                <dd class="col-sm-9">{{ $payment->swap->returnedBattery->serial_number ?? 'N/A' }}</dd>

                <dt class="col-sm-3 fw-semibold">Battery Issued</dt>
                <dd class="col-sm-9">{{ $payment->swap->battery->serial_number ?? 'N/A' }}</dd>
            </dl>

            @if($payment->amount == 0)
                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Note:</strong> This swap was free (possibly first-time rider).
                </div>
            @endif
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection
