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
    {{-- Rider Selection --}}
<div class="mb-3">
    <label for="rider_id" class="form-label fw-semibold">Select Rider</label>
    <select name="rider_id" id="riderSelect"
        class="form-control select2"
        required
        data-placeholder="Search rider by name or phone"
        style="width: 100%;">
        <option value="">-- Select Rider --</option>
        @foreach($riders as $rider)
            <option 
                value="{{ $rider->id }}"
                data-bike-id="{{ optional($rider->purchases->first())->motorcycleUnit->id ?? '' }}"
                data-bike-plate="{{ optional($rider->purchases->first())->motorcycleUnit->number_plate ?? '' }}"
                data-battery-id="{{ optional(\App\Models\Battery::where('current_rider_id', $rider->id)->where('status', 'in_use')->first())->id ?? '' }}"
                data-battery-serial="{{ optional(\App\Models\Battery::where('current_rider_id', $rider->id)->where('status', 'in_use')->first())->serial_number ?? '' }}"
            >
                {{ $rider->name }} ({{ $rider->phone }})
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

@push('scripts')
<script>
    $(document).ready(function () {
        const batteryInput = $('#battery');
        const payableInput = $('#payable');
        const payableDisplay = $('#payable_display');
        const riderSelect = $('#riderSelect');
        const batteryReturnedSelect = $('#batteryReturnedSelect');
        const motorcycleDisplay = $('#motorcycleDisplay');
        const motorcycleUnitId = $('#motorcycleUnitId');
        const basePrice = {{ config('billing.base_price') ?? 15000 }};

        // Initialize Select2
        $('#riderSelect').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $('#riderSelect').data('placeholder'),
            allowClear: true,
            dropdownAutoWidth: true,
            minimumResultsForSearch: 0
        });


        // Battery percentage input logic
        batteryInput.on('input', function () {
            const value = parseFloat($(this).val());
            if (!isNaN(value) && value >= 0 && value <= 100) {
                const missing = 100 - value;
                const amount = (missing / 100) * basePrice;
                payableInput.val(amount.toFixed(2));
                payableDisplay.val(amount.toLocaleString('en-UG', {
                    style: 'currency',
                    currency: 'UGX'
                }));
            } else {
                payableInput.val('');
                payableDisplay.val('');
            }
        });

        // Rider selection logic
        riderSelect.on('change', function () {
            const selected = this.options[this.selectedIndex];

            motorcycleDisplay.val(selected.getAttribute('data-bike-plate') || 'N/A');
            motorcycleUnitId.val(selected.getAttribute('data-bike-id') || '');

            const batteryId = selected.getAttribute('data-battery-id');
            const batterySerial = selected.getAttribute('data-battery-serial');

            if (batteryId && batterySerial) {
                batteryReturnedSelect.html(`<option value="${batteryId}" selected>${batterySerial}</option>`);
            } else {
                batteryReturnedSelect.html('<option value="">No battery found</option>');
            }
        });
    });
</script>
@endpush

@endsection
