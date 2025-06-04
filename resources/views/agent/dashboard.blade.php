@extends('agent.layouts.app')

@section('content')
<div class="px-4 py-4 space-y-6">

    <!-- 🔍 Filter Bar - Horizontal Layout -->
    <!-- 📍 Agent's Station Details -->
<div class="alert alert-secondary d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
    <div>
        <h6 class="mb-1"><i class="bi bi-geo-alt me-1"></i> Station: <strong>{{ $station->name }}</strong></h6>
    </div>
</div>


    <!-- ✅ Highlighted Summary Box -->
    <div class="alert alert-success text-center fw-bold fs-6">
        Total Swaps Revenue Collected: UGX {{ number_format($totalRevenue) }}
    </div>

    <!-- 📦 Stat Blocks -->
    <div class="d-flex flex-wrap gap-3 mb-4">
        <div class="bg-primary text-white p-3 rounded text-center flex-fill shadow-sm" style="min-width: 120px;">
            <div class="fs-3 fw-bold">{{ $totalSwaps }}</div>
            <div class="small"><i class="bi bi-arrow-repeat me-1"></i>Total Swaps</div>
        </div>
        <div class="bg-success text-white p-3 rounded text-center flex-fill shadow-sm" style="min-width: 120px;">
            <div class="fs-3 fw-bold">UGX {{ number_format($totalRevenue) }}</div>
            <div class="small"><i class="bi bi-cash-stack me-1"></i>Total Revenue</div>
        </div>
    </div>


    <!-- 📈 Weekly Chart -->
    <div class="bg-white rounded shadow p-4">
        <h5 class="text-lg font-semibold mb-3">Swaps This Week</h5>
        <canvas id="swapChart" height="100"></canvas>
    </div>

    <!-- 📜 Swap History Timeline -->
    <div class="bg-white rounded shadow p-4">
        <h5 class="text-lg font-semibold mb-4">Swap History Timeline</h5>
        <div class="border-l-4 border-blue-500 space-y-4 pl-6">
            @foreach($swapTimeline as $swap)
            <div class="relative">
                <div class="absolute -left-3 top-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm">
                    <i class="bi bi-battery-charging"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-700">
                        <strong>{{ $swap->riderUser->name ?? 'Unknown Rider' }}</strong>
                        swapped at <strong>{{ $swap->station->name ?? 'Unknown Station' }}</strong>
                        — {{ $swap->percentage_difference }}% diff, UGX {{ number_format($swap->payable_amount) }}
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
            labels: {!! json_encode($chartData->pluck('date')) !!},
            datasets: [{
                label: 'Swaps per Day',
                data: {!! json_encode($chartData->pluck('count')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6'
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
