@extends('admin.layouts.app')

@section('title','Wallet Ledger')

@section('content')
<h4 class="fw-bold text-primary mb-3">
    <i class="bi bi-list-ul me-2"></i>Wallet Ledger – {{ $user->name }}
</h4>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Reference</th>
                        <th class="text-end">Amount (UGX)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('l, d-M-Y h:i A') }}</td>
                            <td>{{ ucfirst(str_replace('_',' ', $log->reason)) }}</td>
                            <td>{{ $log->reference ?? '—' }}</td>
                            <td class="text-end {{ $log->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $log->type === 'credit' ? '+' : '-' }}{{ number_format($log->amount, 0) }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">
    {{ $logs->links('pagination::bootstrap-5') }}
</div>
@endsection
