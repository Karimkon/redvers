@extends('finance.layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container">
    <h2 class="mb-4">Finance Reports</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body text-center">
                    <h4>Total Revenue</h4>
                    <p class="fs-5 fw-bold">UGX {{ number_format($totalPayments) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-left-success">
                <div class="card-body text-center">
                    <h4>Total Payments</h4>
                    <p class="fs-5 fw-bold">{{ $paymentCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body text-center">
                    <h4>Total Swaps</h4>
                    <p class="fs-5 fw-bold">{{ $swapCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Download Reports</div>
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row gap-3 flex-wrap">
    <a href="{{ route('finance.reports.download', 'payments') }}" class="btn btn-outline-primary">
        <i class="bi bi-download me-1"></i> Download Payments PDF
    </a>

    <a href="{{ route('finance.reports.download', 'swaps') }}" class="btn btn-outline-success">
        <i class="bi bi-download me-1"></i> Download Swaps PDF
    </a>

    {{-- 📊 Income Statement --}}
    <a href="{{ route('finance.income.export', ['format' => 'excel']) }}" class="btn btn-outline-info">
        <i class="bi bi-file-earmark-excel me-1"></i> Income Statement (Excel)
    </a>
    <a href="{{ route('finance.income.export', ['format' => 'pdf']) }}" class="btn btn-outline-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i> Income Statement (PDF)
    </a>

    {{-- 🧾 Balance Sheet --}}
    <a href="{{ route('finance.balance.export', ['format' => 'excel']) }}" class="btn btn-outline-warning">
        <i class="bi bi-file-earmark-excel me-1"></i> Balance Sheet (Excel)
    </a>
    <a href="{{ route('finance.balance.export', ['format' => 'pdf']) }}" class="btn btn-outline-dark">
        <i class="bi bi-file-earmark-pdf me-1"></i> Balance Sheet (PDF)
    </a>
</div>

        </div>
    </div>
</div>
@endsection
