@extends('mechanic.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-3 py-4">
    <h3 class="mb-4">🔧 Welcome, {{ $user->name }}</h3>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="bg-primary text-white p-4 rounded shadow-sm text-center">
                <h4 class="fw-bold">{{ $totalBikes }}</h4>
                <p>Total Motorcycles in Company</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-success text-white p-4 rounded shadow-sm text-center">
                <h4 class="fw-bold">{{ $repairsDone }}</h4>
                <p>Repairs You've Worked On</p>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">🗓️ Maintenance Activity (Last 7 Days)</h5>
            <canvas id="repairChart" height="100"></canvas>
        </div>
    </div>

    {{-- Recent Repairs --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">🛠️ Recent Repairs</h5>
            <ul class="list-group list-group-flush">
                @forelse($recentRepairs as $repair)
                    <li class="list-group-item">
                        <strong>{{ $repair->motorcycle->number_plate ?? 'Unknown Bike' }}</strong><br>
                        Issue: {{ $repair->issue }}<br>
                        <small class="text-muted">{{ $repair->created_at->diffForHumans() }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No recent repairs found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

{{-- Chart Script --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('repairChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData->pluck('date')) !!},
            datasets: [{
                label: 'Repairs Per Day',
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
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
