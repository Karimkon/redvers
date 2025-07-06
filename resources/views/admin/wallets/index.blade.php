@extends('admin.layouts.app')

@section('title', 'Wallet Management')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 🔹 Page header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-primary">
                <i class="bi bi-wallet2 me-2"></i> Wallet Management
            </h3>
            <small class="text-muted">Monitor &amp; top‑up rider balances in real-time.</small>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2 align-items-start align-items-sm-center">
            <span class="badge bg-dark fs-6 py-2 px-3 text-wrap shadow-sm">
                <i class="bi bi-coin"></i>
                Grand&nbsp;Total:&nbsp;
                <strong>UGX {{ number_format($totalBalance, 0) }}</strong>
            </span>
            <a href="{{ route('admin.wallets.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </a>
        </div>
    </div>

    {{-- 🔹 Desktop table (≥ md) --}}
    <div class="card shadow rounded-4 d-none d-md-block">
        <div class="card-header bg-light rounded-top-4 px-4 py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-primary">Wallet Overview</h5>
                <span class="text-muted small">Total Riders: {{ $wallets->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="align-middle text-nowrap">
                            <th class="ps-4" style="width: 28%;">Rider</th>
                            <th style="width: 26%;">Email</th>
                            <th style="width: 14%;">Role</th>
                            <th class="text-end" style="width: 18%;">Balance</th>
                            <th class="text-center" style="width: 14%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallets as $wallet)
                            <tr class="align-middle text-nowrap">
                                <td class="ps-4 fw-medium">{{ $wallet->user->name }}</td>
                                <td class="text-muted">{{ $wallet->user->email ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-gradient bg-secondary">{{ ucfirst($wallet->user->role) }}</span>
                                </td>
                                <td class="text-end fw-semibold text-success">UGX {{ number_format($wallet->balance, 0) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.wallets.topup', $wallet->user) }}" class="btn btn-sm btn-success shadow-sm" title="Top-Up Balance">
                                        <i class="bi bi-plus-circle"></i>
                                    </a>
                                    <a href="{{ route('admin.wallets.show', $wallet->user) }}" class="btn btn-sm btn-outline-primary shadow-sm ms-1" title="View Ledger">
                                        <i class="bi bi-list"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No wallets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🔹 Mobile cards (< md) --}}
    <div class="d-md-none">
        @forelse($wallets as $wallet)
            <div class="card shadow-sm mb-3 rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="me-2">
                            <h6 class="mb-0 fw-semibold text-primary">{{ $wallet->user->name }}</h6>
                            <small class="d-block text-muted">{{ $wallet->user->email ?? '—' }}</small>
                            <span class="badge bg-gradient bg-secondary mt-1">{{ ucfirst($wallet->user->role) }}</span>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold d-block text-success">UGX {{ number_format($wallet->balance, 0) }}</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.wallets.topup', $wallet->user) }}" class="btn btn-sm btn-success w-50 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Top‑Up
                        </a>
                        <a href="{{ route('admin.wallets.show', $wallet->user) }}" class="btn btn-sm btn-outline-primary w-50 shadow-sm">
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
        {{ $wallets->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
