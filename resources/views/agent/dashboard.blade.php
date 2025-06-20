@extends('agent.layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 📍 Station Info --}}
    <div class="alert alert-primary d-flex align-items-center justify-content-between shadow-sm rounded">
        <div>
            <h5 class="mb-0">
                <i class="bi bi-geo-alt-fill me-2"></i>
                Station: <strong>{{ $station->name }}</strong>
            </h5>
        </div>
    </div>

    {{-- ✅ Summary Box --}}
    <div class="alert alert-success text-center fw-semibold fs-6 shadow-sm rounded">
        Total Swaps Revenue Collected: UGX {{ number_format($totalRevenue) }}
    </div>

    {{-- 📦 Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="bg-primary text-white rounded shadow-sm p-4 text-center">
                <div class="fs-2 fw-bold">{{ $totalSwaps }}</div>
                <div><i class="bi bi-arrow-repeat me-1"></i>Total Swaps</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-success text-white rounded shadow-sm p-4 text-center">
                <div class="fs-2 fw-bold">UGX {{ number_format($totalRevenue) }}</div>
                <div><i class="bi bi-cash-stack me-1"></i>Total Revenue</div>
            </div>
        </div>
    </div>

    {{-- 📈 Weekly Chart --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Swaps This Week</h5>
            <canvas id="swapChart" height="100"></canvas>
        </div>
    </div>

    {{-- 📜 Timeline --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Swap History Timeline</h5>

            <div class="timeline border-start border-3 ps-3">
                @foreach($swapTimeline as $swap)
                    <div class="position-relative mb-4 ps-3">
                        <div class="position-absolute top-0 start-0 translate-middle p-2 bg-primary border border-light rounded-circle">
                            <i class="bi bi-battery-charging text-white"></i>
                        </div>
                        <p class="mb-1">
                            <strong>{{ $swap->riderUser->name ?? 'Unknown Rider' }}</strong>
                            swapped at <strong>{{ $swap->station->name ?? 'Unknown Station' }}</strong><br>
                            <span class="text-muted small">
                                {{ $swap->percentage_difference }}% diff, UGX {{ number_format($swap->payable_amount) }}
                            </span>
                        </p>
                        <small class="text-secondary">{{ $swap->created_at->format('d M Y, H:i') }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- 📊 Chart.js --}}
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
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#0d6efd'
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
