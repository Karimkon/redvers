@extends('finance.layouts.app')
@section('title', 'Balance Sheet')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">📑 Balance Sheet</h4>

    {{-- 🔍 Date Filters --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-4 align-self-end d-flex gap-2">
            <button class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </form>

    {{-- 📤 Export Buttons --}}
    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('finance.balance.export', ['format' => 'pdf', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
        <a href="{{ route('finance.balance.export', ['format' => 'xlsx', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>

    {{-- 🧾 Balance Sheet Summary --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Balance Sheet Summary:</strong> 
                Assets: UGX {{ number_format(($bankCash ?? 0) + ($stocks ?? 0)) }} | 
                Liabilities: UGX {{ number_format(($loanBalance ?? 0) + ($taxes ?? 0) + ($payables ?? 0)) }} | 
                Equity: UGX {{ number_format(($investorShares ?? 0) + ($retainedEarnings ?? 0)) }}
            </div>
        </div>
    </div>

    {{-- 🧾 Balance Sheet Sections --}}
    <div class="row g-4">
        {{-- ✅ Assets --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bi bi-cash-stack"></i> Assets
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Cash & Bank</span>
                        <span class="fw-bold">UGX {{ number_format($bankCash ?? 0) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Stock Inventory</span>
                        <span class="fw-bold">UGX {{ number_format($stocks ?? 0) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span class="fw-bold">Total Assets</span>
                        <span class="fw-bold text-success">UGX {{ number_format(($bankCash ?? 0) + ($stocks ?? 0)) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- ❗ Liabilities --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="bi bi-exclamation-triangle"></i> Liabilities
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Loan Balance</span>
                        <span class="fw-bold">UGX {{ number_format($loanBalance ?? 0) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Taxes (Est.)</span>
                        <span class="fw-bold">UGX {{ number_format($taxes ?? 0) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Accounts Payable</span>
                        <span class="fw-bold">UGX {{ number_format($payables ?? 0) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span class="fw-bold">Total Liabilities</span>
                        <span class="fw-bold text-danger">UGX {{ number_format(($loanBalance ?? 0) + ($taxes ?? 0) + ($payables ?? 0)) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- 📘 Equity --}}
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-people"></i> Equity
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Investor Shares</span>
                        <span class="fw-bold">UGX {{ number_format($investorShares ?? 0) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Retained Earnings</span>
                        <span class="fw-bold {{ ($retainedEarnings ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                            UGX {{ number_format($retainedEarnings ?? 0) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span class="fw-bold">Total Equity</span>
                        <span class="fw-bold text-primary">UGX {{ number_format(($investorShares ?? 0) + ($retainedEarnings ?? 0)) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- 📊 Balance Verification --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white fw-bold">
                    <i class="bi bi-check-circle"></i> Balance Verification
                </div>
                <div class="card-body">
                    @php
                        $totalAssets = ($bankCash ?? 0) + ($stocks ?? 0);
                        $totalLiabilitiesAndEquity = ($loanBalance ?? 0) + ($taxes ?? 0) + ($payables ?? 0) + ($investorShares ?? 0) + ($retainedEarnings ?? 0);
                        $isBalanced = $totalAssets == $totalLiabilitiesAndEquity;
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Assets:</strong> UGX {{ number_format($totalAssets) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Liabilities & Equity:</strong> UGX {{ number_format($totalLiabilitiesAndEquity) }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        @if($isBalanced)
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle"></i> Balance Sheet is BALANCED ✅
                            </span>
                        @else
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-exclamation-triangle"></i> 
                                Difference: UGX {{ number_format($totalAssets - $totalLiabilitiesAndEquity) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 📝 Notes --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="bi bi-journal-text"></i> Notes
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Assets include cash/bank balances and inventory stock values</li>
                        <li>Loan balances are pulled from the Loans module</li>
                        <li>Taxes are estimated at 10% of operating expenses</li>
                        <li>Accounts payable estimated at 20% of operating expenses</li>
                        <li>Retained earnings = Total Revenue - COGS - Operating Expenses - Loan Payments</li>
                        <li>Period: {{ $start ?? 'N/A' }} to {{ $end ?? 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection