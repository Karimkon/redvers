@extends('rider.layouts..app')

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

    <!-- Rider Summary -->
    <div class="d-flex flex-wrap gap-3 mb-4">
        <div class="bg-danger text-white p-3 rounded text-center flex-fill shadow-sm" style="min-width: 120px;">
            <div class="fs-3 fw-bold">UGX {{ number_format($remainingBalance) }}</div>
            <div class="small"><i class="bi bi-wallet2 me-1"></i>Remaining Balance</div>
        </div>

        <div class="bg-primary text-white p-3 rounded text-center flex-fill shadow-sm" style="min-width: 120px;">
            <div class="fs-3 fw-bold">{{ $totalSwaps }}</div>
            <div class="small"><i class="bi bi-arrow-repeat me-1"></i>Total Swaps</div>
        </div>
        <!-- <div class="bg-success text-white p-3 rounded text-center flex-fill shadow-sm" style="min-width: 120px;">
            <div class="fs-3 fw-bold">UGX {{ number_format($totalRevenue) }}</div>
            <div class="small"><i class="bi bi-cash-stack me-1"></i>Total Paid</div>
        </div> -->
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

    
    @if($scheduleSummary)
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
        <div class="border-l-4 border-purple-500 space-y-4 pl-6">
            @foreach($recentSwaps as $swap)
                <div class="relative">
                    <div class="absolute -left-3 top-0 w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm">
                        <i class="bi bi-battery-charging"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">
                            Swapped at <strong>{{ $swap->station->name ?? 'Unknown Station' }}</strong>
                            — Battery Issued: {{ $swap->battery?->serial_number ?? 'N/A' }} and you returned: {{ $swap->returnedBattery->serial_number ?? 'None' }}, You paid
 UGX {{ number_format($swap->payable_amount) }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $swap->created_at->format('d M Y, H:i') }}</p>
                    </div>
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
