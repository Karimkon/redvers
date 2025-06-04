@extends('agent.layouts.app')

@section('title', 'Create Swap')

@section('content')
<div class="container">
    <h2 class="mb-4">Initiate New Swap</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('agent.swaps.store') }}" method="POST" id="swapForm">
        @csrf

        {{-- Rider Selection --}}
        <div class="mb-3">
            <label for="rider_id" class="form-label">Rider</label>
            <select class="form-select" name="rider_id" id="riderSelect" required>
                <option value="">Select Rider</option>
                @foreach($riders as $rider)
                    @php
                        $unit = optional($rider->purchases->first())->motorcycleUnit;
                        $battery = \App\Models\Battery::where('current_rider_id', $rider->id)
                                    ->where('status', 'in_use')
                                    ->first();
                    @endphp
                    <option 
                        value="{{ $rider->id }}"
                        data-bike-id="{{ $unit->id ?? '' }}"
                        data-bike-plate="{{ $unit->number_plate ?? '' }}"
                        data-battery-id="{{ $battery->id ?? '' }}"
                        data-battery-serial="{{ $battery->serial_number ?? '' }}"
                    >
                        {{ $rider->name }} ({{ $rider->email }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Motorcycle Unit (Auto-filled) --}}
        <div class="mb-3">
            <label class="form-label">Motorcycle (Auto-detected)</label>
            <input type="text" id="motorcycleDisplay" class="form-control" readonly>
            <input type="hidden" name="motorcycle_unit_id" id="motorcycleUnitId">
        </div>

        {{-- Station --}}
        <div class="mb-3">
            <label class="form-label">Station</label>
            <div class="form-control-plaintext fw-bold">
                {{ Auth::user()->station->name ?? 'N/A' }}
            </div>
            <input type="hidden" name="station_id" value="{{ Auth::user()->station_id }}">
        </div>

        {{-- Returned Battery --}}
        <div class="mb-3" id="returnedBatteryGroup">
            <label class="form-label">Battery Returned by Rider</label>
            <select class="form-select" name="battery_returned_id" id="batteryReturnedSelect">
                <option value="">Select Rider First</option>
            </select>
        </div>

        {{-- New Battery --}}
        <div class="mb-3">
            <label for="battery_id" class="form-label">Available Battery</label>
            <select class="form-select" name="battery_id" required>
                <option value="">Select Battery</option>
                @foreach($availableBatteries as $battery)
                    <option value="{{ $battery->id }}">{{ $battery->serial_number }}</option>
                @endforeach
            </select>
        </div>

        {{-- Percentage --}}
        <div class="mb-3">
            <label for="percentage_difference" class="form-label">Battery Percentage</label>
            <input type="number" name="percentage_difference" id="battery" class="form-control" min="0" max="100" required>
            <small class="text-muted">Enter battery percentage returned (0–100)</small>
        </div>

        {{-- Amount --}}
        <div class="mb-3">
            <label class="form-label">Payable Amount (UGX)</label>
            <input type="text" id="payable_display" class="form-control" readonly>
            <input type="hidden" name="payable_amount" id="payable">
        </div>

        {{-- Payment --}}
        <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select">
                <option value="">None</option>
                <option value="mtn">MTN MoMo</option>
                <option value="airtel">Airtel Money</option>
                <option value="pesapal">Pesapal</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle me-1"></i> Create Swap
        </button>
        <a href="{{ route('agent.swaps.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

<script>
    const batteryInput = document.getElementById('battery');
    const payableInput = document.getElementById('payable');
    const payableDisplay = document.getElementById('payable_display');
    const riderSelect = document.getElementById('riderSelect');
    const batteryReturnedSelect = document.getElementById('batteryReturnedSelect');
    const motorcycleDisplay = document.getElementById('motorcycleDisplay');
    const motorcycleUnitId = document.getElementById('motorcycleUnitId');
    const basePrice = {{ config('billing.base_price') ?? 15000 }};

    batteryInput.addEventListener('input', () => {
        const value = parseFloat(batteryInput.value);
        if (!isNaN(value) && value >= 0 && value <= 100) {
            const missing = 100 - value;
            const amount = (missing / 100) * basePrice;
            payableInput.value = amount.toFixed(2);
            payableDisplay.value = amount.toLocaleString('en-UG', {
                style: 'currency', currency: 'UGX'
            });
        } else {
            payableInput.value = '';
            payableDisplay.value = '';
        }
    });

    riderSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        motorcycleDisplay.value = selected.getAttribute('data-bike-plate') || 'N/A';
        motorcycleUnitId.value = selected.getAttribute('data-bike-id') || '';

        // Set returned battery manually
        const batteryId = selected.getAttribute('data-battery-id');
        const batterySerial = selected.getAttribute('data-battery-serial');

        if (batteryId && batterySerial) {
            batteryReturnedSelect.innerHTML = `<option value="${batteryId}" selected>${batterySerial}</option>`;
        } else {
            batteryReturnedSelect.innerHTML = `<option value="">No battery found</option>`;
        }
    });
</script>
@endsection
