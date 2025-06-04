@extends('admin.layouts.app')

@section('title', 'View Payment Details')

@section('content')
<div class="container">
    <h2 class="mb-4">Payment Details</h2>

    <div class="mb-3">
    <input type="text" id="paymentSearch" class="form-control" placeholder="Search by rider, method, reference, amount...">
</div>


    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Rider Name</dt>
                <dd class="col-sm-9">{{ $payment->swap->rider->name ?? 'Unknown' }}</dd>

                <dt class="col-sm-3">Rider Phone</dt>
                <dd class="col-sm-9">{{ $payment->swap->rider->phone ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Rider Email</dt>
                <dd class="col-sm-9">{{ $payment->swap->rider->email ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Amount Paid</dt>
                <dd class="col-sm-9 fw-bold text-primary">{{ number_format($payment->amount, 0) }} UGX</dd>

                <dt class="col-sm-3">Payment Method</dt>
                <dd class="col-sm-9">{{ ucfirst($payment->method) }}</dd>

                <dt class="col-sm-3">Payment Status</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-{{ 
                        $payment->status == 'completed' ? 'success' : 
                        ($payment->status == 'pending' ? 'warning' : 'danger') 
                    }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </dd>

                <dt class="col-sm-3">Reference ID</dt>
                <dd class="col-sm-9">{{ $payment->reference }}</dd>

                <dt class="col-sm-3">Initiated By</dt>
                <dd class="col-sm-9">{{ ucfirst($payment->initiated_by ?? 'admin') }}</dd>

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $payment->created_at->format('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Related Swap</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('admin.swaps.show', $payment->swap_id) }}" class="text-decoration-underline">
                        View Swap #{{ $payment->swap_id }}
                    </a>
                </dd>

                <dt class="col-sm-3">Battery Returned</dt>
                <dd class="col-sm-9">{{ $payment->swap->returnedBattery->serial_number ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Battery Issued</dt>
                <dd class="col-sm-9">{{ $payment->swap->battery->serial_number ?? 'N/A' }}</dd>
            </dl>

            @if($payment->amount == 0)
                <div class="alert alert-info mt-4">
                    <strong>Note:</strong> This swap was free (possibly first-time rider).
                </div>
            @endif
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
