@extends('agent.layouts.app')

@section('title', 'Swap History')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 🔍 Header + Search --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-clock-history me-2"></i> Detailed Swap History
        </h4>
        <form method="GET" class="d-flex flex-wrap gap-2">
            <input 
                type="text" 
                name="search" 
                class="form-control shadow-sm" 
                placeholder="Search name, phone, or plate" 
                value="{{ request('search') }}"
            >
            <button class="btn btn-outline-primary shadow-sm" type="submit">
                <i class="bi bi-search"></i> Search
            </button>
        </form>
    </div>

    {{-- ⚠️ Empty State --}}
    @if ($swaps->isEmpty())
        <div class="alert alert-warning shadow-sm">
            <i class="bi bi-exclamation-circle me-1"></i> No swap history found.
        </div>
    @else
        {{-- 📋 Swap History Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Date</th>
                                <th>Rider</th>
                                <th>Motorcycle</th>
                                <th>Station</th>
                                <th>Battery %</th>
                                <th>Amount</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($swaps as $swap)
                                <tr class="text-center">
                                    <td>{{ $swap->swapped_at->format('d M Y, H:i') }}</td>
                                    <td class="text-start">{{ $swap->riderUser->name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $swap->motorcycleUnit->number_plate ?? '—' }}<br>
                                        <small class="text-muted">ID: {{ $swap->motorcycle_unit_id }}</small>
                                    </td>
                                    <td>{{ $swap->station->name ?? 'N/A' }}</td>
                                    <td>{{ $swap->percentage_difference }}%</td>
                                    <td>UGX {{ number_format($swap->payable_amount) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ strtoupper($swap->payment_method ?? '-') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{ $swaps->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </ul>
                </nav>
            </div>
    @endif
</div>
@endsection
