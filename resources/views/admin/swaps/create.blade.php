@extends('admin.layouts.app')

@section('title', 'Create Swap')

@section('content')
<h2>Create New Swap</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.swaps.store') }}" id="swapForm">
    @csrf

    {{-- Rider --}}
    <div class="mb-3">
        <label class="form-label">Rider</label>
        <select name="rider_id" class="form-select" id="riderSelect" required>
            <option value="">Select Rider</option>
            @foreach($riders as $rider)
                @php
                    $unit = optional($rider->purchases->first())->motorcycleUnit;
                @endphp
                <option 
                    value="{{ $rider->id }}"
                    data-bike-id="{{ $unit->id ?? '' }}"
                    data-bike-plate="{{ $unit->number_plate ?? '' }}"
                >
                    {{ $rider->name }} ({{ $rider->phone }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- Returned Battery --}}
    <div class="mb-3" id="returnedBatteryGroup">
        <label class="form-label">Returned Battery</label>
        <select name="battery_returned_id" class="form-select" id="batteryReturnedSelect">
            <option value="">Select Rider First</option>
        </select>
        <small class="text-muted">If the rider has no previous battery, leave this empty.</small>
    </div>

    {{-- Station --}}
    <div class="mb-3">
        <label class="form-label">Station</label>
        <select name="station_id" class="form-select" id="stationSelect" required>
            <option value="">Select Station</option>
            @foreach($stations as $station)
                <option value="{{ $station->id }}">{{ $station->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Agent --}}
    <div class="mb-3">
        <label class="form-label">Agent (optional)</label>
        <select name="agent_id" class="form-select">
            <option value="">Select Agent</option>
            @foreach($agents as $agent)
                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Battery to Assign --}}
    <div class="mb-3">
        <label class="form-label">Battery to Assign</label>
        <select name="battery_id" class="form-select" id="batterySelect" required>
            <option value="">Select Station First</option>
        </select>
        <small class="text-muted">Only batteries in stock or charging will be shown</small>
    </div>

    {{-- Motorcycle --}}
    <div class="mb-3">
        <label class="form-label">Motorcycle (Auto-detected)</label>
        <input type="text" id="motorcycleDisplay" class="form-control" readonly style="background: #f8f9fa;">
        <input type="hidden" name="motorcycle_unit_id" id="motorcycleUnitId">
    </div>

    {{-- Battery Percentage --}}
    <div class="mb-3">
        <label class="form-label">Battery Percentage</label>
        <input type="number" name="percentage_difference" id="battery" class="form-control" min="0" max="100" required>
        <small class="text-muted">Enter current battery percentage (0 to 100)</small>
    </div>

    {{-- Payable Amount --}}
    <div class="mb-3">
        <label class="form-label">Payable Amount (UGX)</label>
        <input type="text" id="payable_display" class="form-control" readonly>
        <input type="hidden" name="payable_amount" id="payable">
    </div>

    {{-- Payment Method --}}
    <div class="mb-3">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-select" id="paymentMethod">
            <option value="">None</option>
            <option value="mtn">MTN</option>
            <option value="airtel">Airtel</option>
            <option value="pesapal">Pesapal</option>
        </select>
    </div>

    <button class="btn btn-primary">Submit Swap</button>
</form>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const basePrice = {{ config('billing.base_price') ?? 12000 }};
    const form = document.getElementById('swapForm');

    const batteryInput = document.getElementById('battery');
    const payableInput = document.getElementById('payable');
    const payableDisplay = document.getElementById('payable_display');

    const riderSelect = document.getElementById('riderSelect');
    const batteryReturnedSelect = document.getElementById('batteryReturnedSelect');
    const stationSelect = document.getElementById('stationSelect');
    const batterySelect = document.getElementById('batterySelect');

    const motorcycleDisplay = document.getElementById('motorcycleDisplay');
    const motorcycleUnitId = document.getElementById('motorcycleUnitId');

    // 💰 Billing Logic
    batteryInput.addEventListener('input', () => {
        const val = parseFloat(batteryInput.value);
        if (!isNaN(val) && val >= 0 && val <= 100) {
            const missing = 100 - val;
            const amount = (missing / 100) * basePrice;
            payableInput.value = amount.toFixed(2);
            payableDisplay.value = amount.toLocaleString('en-UG', { style: 'currency', currency: 'UGX' });
        } else {
            payableInput.value = '';
            payableDisplay.value = '';
        }
    });

    // 🛵 Motorcycle via HTML <option data-*>
    riderSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        motorcycleDisplay.value = selected.getAttribute('data-bike-plate') || '❌ No motorcycle assigned';
        motorcycleUnitId.value = selected.getAttribute('data-bike-id') || '';

        // 🔋 Returned Battery still uses API
        batteryReturnedSelect.innerHTML = '<option>Loading...</option>';
        const riderId = this.value;
        fetch(`/admin/api/rider-last-battery/${riderId}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.id) {
                    batteryReturnedSelect.innerHTML = `<option selected value="${data.id}">${data.serial_number}</option>`;
                } else {
                    batteryReturnedSelect.innerHTML = `<option value="">No battery found (new rider)</option>`;
                }
            })
            .catch(() => {
                batteryReturnedSelect.innerHTML = `<option value="">Error loading battery</option>`;
            });
    });

    // 🔋 Available Batteries from Station
    stationSelect.addEventListener('change', function () {
        const stationId = this.value;
        batterySelect.innerHTML = '<option>Loading...</option>';
        fetch(`/admin/api/batteries-by-station/${stationId}`)
            .then(res => res.json())
            .then(data => {
                batterySelect.innerHTML = data.length
                    ? data.map(b => `<option value="${b.id}">${b.serial_number}</option>`).join('')
                    : '<option value="">No batteries available</option>';
                $('#batterySelect').select2({ placeholder: "Select battery..." });
            })
            .catch(() => {
                batterySelect.innerHTML = '<option value="">Failed to load batteries</option>';
            });
    });

    // 🛑 Block if no motorcycle
    form.addEventListener('submit', function (e) {
        if (!motorcycleUnitId.value) {
            e.preventDefault();
            alert('⚠️ No motorcycle assigned to this rider.');
        }
    });

    // 🎨 Init Select2
    $(document).ready(function () {
        $('#batterySelect').select2({ placeholder: "Select battery..." });
    });
</script>
@endpush
