@extends('finance.layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="mb-3">💳 All Payments</h2>
    </div>

    <div class="table-responsive shadow-sm bg-white rounded">
        <table class="table table-bordered align-middle text-nowrap mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Rider</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Reference</th>
                    <th>Initiated By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + $payments->firstItem() }}</td>
                        <td>{{ $payment->swap->rider->name ?? 'Unknown' }}</td>
                        <td>{{ $payment->swap->rider->email ?? '-' }}</td>
                        <td>{{ $payment->swap->rider->phone ?? '-' }}</td>
                        <td>{{ number_format($payment->amount, 0) }} UGX</td>
                        <td>
                            <span class="badge bg-{{ 
                                $payment->status == 'completed' ? 'success' : 
                                ($payment->status == 'pending' ? 'warning' : 'danger') 
                            }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->reference }}</td>
                        <td>{{ ucfirst($payment->initiated_by ?? 'admin') }}</td>
                        <td>
                            <a href="{{ route('finance.payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
@endsection
