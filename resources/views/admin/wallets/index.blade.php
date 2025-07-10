@extends('admin.layouts.app')

@section('title', 'Wallet Management')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 🔹 Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-primary">
                <i class="bi bi-wallet2 me-2"></i> Wallet Management
            </h3>
            <small class="text-muted">Monitor &amp; top‑up rider balances in real-time.</small>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2 align-items-start align-items-sm-center">
            <span class="badge bg-dark fs-6 py-2 px-3 shadow-sm">
                <i class="bi bi-coin"></i>
                Grand Total: <strong>UGX {{ number_format($totalBalance, 0) }}</strong>
            </span>
            <a href="{{ route('admin.wallets.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </a>
        </div>
    </div>

    {{-- 🔹 Search --}}
    <form method="GET" action="{{ route('admin.wallets.index') }}" class="mb-3">
        <div class="input-group shadow-sm">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name, phone or email">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>

    {{-- 🔹 Wallets Table (Desktop) --}}
    <div class="card shadow rounded-4 d-none d-md-block">
        <div class="card-header bg-light rounded-top-4 px-4 py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-primary">Wallet Overview</h5>
                <span class="text-muted small">Total: {{ $wallets->total() }} riders</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-nowrap align-middle">
                            <th class="ps-4">Rider</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Balance</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallets as $wallet)
                            <tr class="align-middle text-nowrap">
                                <td class="ps-4 fw-medium">{{ $wallet->user->name }}</td>
                                <td class="text-muted">{{ $wallet->user->email ?? '—' }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($wallet->user->role) }}</span></td>
                                <td class="text-end fw-semibold text-success">UGX {{ number_format($wallet->balance, 0) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.wallets.topup', $wallet->user) }}" class="btn btn-sm btn-success" title="Top-Up Balance">
                                        <i class="bi bi-plus-circle"></i>
                                    </a>
                                    <a href="{{ route('admin.wallets.show', $wallet->user) }}" class="btn btn-sm btn-outline-primary ms-1" title="View Ledger">
                                        <i class="bi bi-list"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No wallets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🔹 Mobile View --}}
    <div class="d-md-none">
        @forelse($wallets as $wallet)
            <div class="card shadow-sm mb-3 rounded-4">
                <div class="card-body p-3">
                    <h6 class="fw-semibold text-primary mb-0">{{ $wallet->user->name }}</h6>
                    <small class="text-muted">{{ $wallet->user->email ?? '—' }}</small>
                    <span class="badge bg-gradient bg-secondary mt-1">{{ ucfirst($wallet->user->role) }}</span>
                    <div class="text-end mt-2">
                        <span class="fw-bold text-success">UGX {{ number_format($wallet->balance, 0) }}</span>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('admin.wallets.topup', $wallet->user) }}" class="btn btn-sm btn-success w-50">
                            <i class="bi bi-plus-circle me-1"></i> Top-Up
                        </a>
                        <a href="{{ route('admin.wallets.show', $wallet->user) }}" class="btn btn-sm btn-outline-primary w-50">
                            <i class="bi bi-list"></i> Ledger
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No wallets found.</p>
        @endforelse
    </div>

    {{-- 🔹 Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $wallets->appends(['q' => request('q')])->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
