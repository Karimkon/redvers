@extends('rider.layouts.app')

@section('content')
<div class="px-4 py-4 space-y-6">

    <!-- Rider Welcome -->
    <div class="alert alert-secondary d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
        <div>
            <h6 class="mb-1">
                <i class="bi bi-person-badge me-1"></i> Welcome back, <strong>{{ $rider->name }}</strong>
            </h6>
        </div>
    </div>

    <!-- Horizontal Summary: Remaining + Due -->
    <div class="d-flex flex-wrap gap-3 mb-4">

        <!-- Remaining Balance with Pay Now -->
        <div class="bg-danger text-white p-3 rounded text-center flex-fill shadow-sm d-flex flex-column justify-content-between" style="min-width: 150px;">
            <div>
                <div class="fs-4 fw-bold">UGX {{ number_format($remainingBalance) }}</div>
                <div class="small"><i class="bi bi-wallet2 me-1"></i>Remaining Balance</div>
            </div>
            <a href="{{ route('rider.daily-payment.create') }}" class="btn btn-sm btn-light mt-3">
                <i class="bi bi-credit-card me-1"></i> Pay Now
            </a>
        </div>


        <!-- Due Amount (only if purchase not completed) -->
        @if($purchase && $purchase->status !== 'completed' && $overdueSummary)
        <div class="bg-warning text-dark p-3 rounded text-center flex-fill shadow-sm" style="min-width: 150px;">
            <div class="fs-4 fw-bold">UGX {{ number_format($overdueSummary['due_amount']) }}</div>
            <div class="small"><i class="bi bi-exclamation-circle me-1"></i>Due Now</div>
            <div class="text-muted small mt-1">
                <i class="bi bi-calendar-event me-1"></i>
                {{ \Carbon\Carbon::parse($overdueSummary['next_due_date'])->translatedFormat('M jS') }}
            </div>
        </div>
        @endif

    </div>

    <!-- Current Battery Info -->
    <div class="alert alert-info text-center fw-bold">
        Current Battery:
        @if ($currentBattery)
            Serial: {{ $currentBattery->serial_number }} | Status: {{ $currentBattery->status }}
        @else
            None assigned
        @endif
    </div>

    <!-- Payment Summary (if applicable) -->
    @if($purchase && $purchase->status !== 'completed' && $scheduleSummary)
    <div class="card shadow-sm mb-4 border-start border-warning border-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-calendar-x me-2 text-warning"></i> Payment Summary</h5>
            <p><strong>Expected Payments:</strong> {{ $scheduleSummary['expected_days'] }}</p>
            <p><strong>Paid:</strong> {{ $scheduleSummary['actual_payments'] }}</p>
            <p>
                <strong>Missed:</strong>
                <span class="badge bg-{{ $scheduleSummary['missed_payments'] > 0 ? 'danger' : 'success' }}">
                    {{ $scheduleSummary['missed_payments'] }}
                </span>
            </p>
            <p>
                <strong>Next Due:</strong>
                <span class="badge bg-info text-dark">
                    {{ \Carbon\Carbon::parse($scheduleSummary['next_due_date'])->translatedFormat('l, M jS') }}
                </span>
            </p>
            <a href="{{ route('rider.schedule') }}" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-list-check me-1"></i> View Full Schedule
            </a>
        </div>
    </div>
    @endif

    <!-- Chart -->
    <div class="bg-white rounded shadow p-4">
        <h5 class="text-lg font-semibold mb-3">Swaps This Week</h5>
        <canvas id="swapChart" height="100"></canvas>
    </div>

    <!-- Swap Timeline -->
    <div class="bg-white rounded shadow p-4">
        <h5 class="text-lg font-semibold mb-4">Recent Swaps</h5>
        <div class="border-start border-purple-500 ps-4 ms-2">
            @foreach($recentSwaps as $swap)
                <div class="mb-3">
                    <div class="text-sm text-dark">
                        Swapped at <strong>{{ $swap->station->name ?? 'Unknown Station' }}</strong>
                        — Battery Issued: {{ $swap->battery?->serial_number ?? 'N/A' }},
                        Returned: {{ $swap->returnedBattery?->serial_number ?? 'None' }},
                        Paid: UGX {{ number_format($swap->payable_amount) }}
                    </div>
                    <div class="text-muted small">{{ $swap->created_at->format('d M Y, H:i') }}</div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('swapChart').getContext('2d');
    const swapChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($swapStats['labels']) !!},
            datasets: [{
                label: 'Swaps per Day',
                data: {!! json_encode($swapStats['counts']) !!},
                borderColor: '#9333ea',
                backgroundColor: 'rgba(147, 51, 234, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#9333ea'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
