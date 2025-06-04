@extends('finance.layouts.app')

@section('title', 'Purchase Details')

@section('content')
    <h4 class="mb-3">Purchase Summary</h4>

    <div class="card p-4 mb-4 shadow-sm">
        <p><strong>Rider:</strong> {{ $purchase->user->name }} ({{ $purchase->user->email }})</p>
        <p><strong>Phone:</strong> {{ $purchase->user->phone }}</p>
        <p><strong>Motorcycle:</strong> {{ ucfirst($purchase->motorcycle->type) }}</p>
        <p><strong>Purchase Type:</strong> {{ ucfirst($purchase->purchase_type) }}</p>
        <p><strong>Total Price:</strong> UGX {{ number_format($purchase->total_price) }}</p>
        <p><strong>Amount Paid:</strong> UGX {{ number_format($purchase->amount_paid) }}</p>
        <p><strong>Remaining Balance:</strong> UGX {{ number_format($purchase->remaining_balance) }}</p>
        <p><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($purchase->status) }}</span></p>
    </div>

    <h5 class="mb-2">Payment History</h5>
    @if($purchase->payments->count())
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td>UGX {{ number_format($payment->amount) }}</td>
                        <td>{{ ucfirst($payment->type) }}</td>
                        <td>{{ $payment->note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No payments recorded yet.</p>
    @endif

    <a href="{{ route('finance.purchases.index') }}" class="btn btn-secondary mt-3">← Back to All Purchases</a>
@endsection
