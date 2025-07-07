@extends('rider.layouts.app')

@section('title', 'Payment Schedule')

@section('content')
<div class="container py-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i>My Payment Schedule</h4>
    </div>

    <!-- Summary Card -->
    <div class="card shadow-sm mb-4 border-start border-primary border-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <p><strong>🏍️ Motorcycle:</strong> {{ ucfirst($purchase->motorcycle->type) }}</p>
                    <p><strong>📅 Start Date:</strong> {{ $purchase->start_date->translatedFormat('F jS, Y') }}</p>
                </div>
                <div class="col-12 col-md-6">
                    <p><strong>📌 Expected Payments:</strong> {{ $schedule['expected_days'] }}</p>
                    <p><strong>✅ Payments Made:</strong> {{ $schedule['actual_payments'] }}</p>
                    <p>
                        <strong>⚠️ Missed Payments:</strong>
                        <span class="badge bg-{{ $schedule['missed_payments'] > 0 ? 'danger' : 'success' }}">
                            {{ $schedule['missed_payments'] }}
                        </span>
                    </p>
                    <p>
                        <strong>🕒 Next Due:</strong>
                        <span class="badge bg-warning text-dark">
                            {{ \Carbon\Carbon::parse($schedule['next_due_date'])->translatedFormat('l, M jS, Y') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Missed Dates -->
    @if(count($schedule['missed_dates']))
    <div class="card shadow-sm border-start border-danger border-4 mb-4">
        <div class="card-header bg-danger text-white fw-semibold">
            <i class="bi bi-exclamation-triangle me-2"></i>Missed Payment Dates ({{ count($schedule['missed_dates']) }})
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush small">
                @foreach($schedule['missed_dates'] as $missed)
                    <li class="list-group-item">{{ $missed }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

</div>
@endsection
