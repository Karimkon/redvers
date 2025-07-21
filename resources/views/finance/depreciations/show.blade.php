@extends('finance.layouts.app')

@section('title', 'Depreciation Details')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-4">Depreciation: {{ $depreciation->product->name ?? '—' }}</h4>

        <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item"><strong>Initial Value:</strong> UGX {{ number_format($depreciation->initial_value) }}</li>
            <li class="list-group-item"><strong>Rate:</strong> {{ $depreciation->depreciation_rate }}%</li>
            <li class="list-group-item"><strong>Lifespan:</strong> {{ $depreciation->lifespan_months ?? '—' }} months</li>
            <li class="list-group-item"><strong>Start Date:</strong> {{ $depreciation->start_date->format('d M Y') }}</li>
            <li class="list-group-item"><strong>Note:</strong> {{ $depreciation->note ?? '—' }}</li>
        </ul>

        {{-- 📊 Static Depreciation Calculation --}}
        @php
            $initial = $depreciation->initial_value;
            $rate = $depreciation->depreciation_rate;
            $months = $depreciation->lifespan_months;

            $totalDep = ($initial * $rate / 100);
            $monthlyDep = ($months > 0) ? ($totalDep / $months) : 0;
            $finalVal = $initial - $totalDep;
        @endphp

        <div class="bg-light p-3 rounded border">
            <h6 class="fw-bold mb-2">📊 Depreciation Summary</h6>
            <ul class="mb-0">
                <li><strong>Total Depreciation:</strong> UGX {{ number_format($totalDep, 2) }}</li>
                <li><strong>Monthly Depreciation:</strong> UGX {{ number_format($monthlyDep, 2) }}</li>
                <li><strong>Final Book Value:</strong> UGX {{ number_format($finalVal, 2) }}</li>
            </ul>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('finance.depreciations.edit', $depreciation) }}" class="btn btn-warning">✏️ Edit</a>
            <a href="{{ route('finance.depreciations.index') }}" class="btn btn-secondary">⬅️ Back</a>
        </div>
    </div>
</div>
@endsection
