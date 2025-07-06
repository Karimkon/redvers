@extends('rider.layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="container py-4">

    {{-- 🔹 Page Title --}}
    <h3 class="mb-4">
        <i class="bi bi-wallet2 me-2 text-primary"></i> My Wallet
    </h3>

    {{-- 🔹 Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        </div>
    @endif

    {{-- 🔹 Wallet Balance Card --}}
    <div class="card shadow-sm mb-4 border-success">
        <div class="card-body d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
            <div>
                <h5 class="mb-1 text-muted">Current Balance</h5>
                <h3 class="fw-bold text-success">UGX {{ number_format($wallet->balance ?? 0, 0) }}</h3>
            </div>
            <a href="{{ route('rider.wallet.topup.form') }}" class="btn btn-primary mt-3 mt-sm-0">
                <i class="bi bi-plus-circle me-1"></i> Top Up Wallet
            </a>
        </div>
    </div>

    {{-- 🔹 Transactions Section --}}
    <h5 class="mb-3">Recent Transactions</h5>

    @if($transactions->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:30%">Date</th>
                        <th class="text-end" style="width:30%">Amount</th>
                        <th style="width:40%">Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $txn)
                        <tr>
                            <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end fw-semibold {{ $txn->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $txn->type === 'credit' ? '+' : '-' }}UGX {{ number_format($txn->amount, 0) }}
                            </td>
                            <td>{{ $txn->reference }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-1"></i> No transactions found.
        </div>
    @endif
</div>
@endsection