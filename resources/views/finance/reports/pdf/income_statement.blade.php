@extends('finance.layouts.app')

@section('title', 'Income Statement')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">📊 Income Statement</h4>

    {{-- 🔍 Date Filters --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-4 align-self-end">
            <button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </form>

    {{-- 📁 Export Buttons --}}
    <div class="d-flex gap-2 mt-2 mb-4">
        <a href="{{ route('finance.income.export', ['format' => 'pdf', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
        <a href="{{ route('finance.income.export', ['format' => 'xlsx', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>

    {{-- 📊 Statement Overview --}}
    <div class="card p-4 shadow-sm">
        <h5 class="fw-bold">
            Period: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
        </h5>

        <ul class="list-group mt-3">
            <li class="list-group-item d-flex justify-content-between">
                <span>Total Revenue</span>
                <span>UGX {{ number_format($totalRevenue) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Cost of Goods Sold</span>
                <span>UGX {{ number_format($totalCOGS) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light fw-bold">
                <span>Gross Profit</span>
                <span>UGX {{ number_format($grossProfit) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Operating Expenses</span>
                <span>UGX {{ number_format($operatingExpenses) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light fw-bold">
                <span>EBITDA</span>
                <span>UGX {{ number_format($ebitda) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Depreciation</span>
                <span>UGX {{ number_format($depreciation) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Loan Interest</span>
                <span>UGX {{ number_format($loanInterest) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light fw-bold">
                <span>Pre-Tax Income</span>
                <span>UGX {{ number_format($preTaxIncome) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Tax (30%)</span>
                <span>UGX {{ number_format($tax) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-success text-white fw-bold">
                <span>Net Income</span>
                <span>UGX {{ number_format($netIncome) }}</span>
            </li>
        </ul>
    </div>
</div>
@endsection
