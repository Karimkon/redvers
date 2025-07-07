@extends('agent.layouts.app')

@section('title', 'Create Swap')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- 📝 Page Title --}}
    <div class="mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-battery-charging me-2"></i> Initiate New Swap
        </h4>
    </div>

    {{-- ❌ Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🚀 Swap Form --}}
    <form action="{{ route('agent.swaps.store') }}" method="POST" id="swapForm" class="card shadow-sm border-0 p-4">
        @csrf

        {{-- 👤 Rider Selection --}}
        <div class="mb-3">
            <label for="rider_id" class="form-label fw-semibold">Select Rider</label>
            <select name="rider_id" id="riderSelect" class="form-select select2" required>
                <option value="">-- Select Rider --</option>
                @foreach($riders as $rider)
                    <option 
                        value="{{ $rider->id }}"
                        data-bike-id="{{ optional($rider->purchases->first())->motorcycleUnit->id ?? '' }}"
                        data-bike-plate="{{ optional($rider->purchases->first())->motorcycleUnit->number_plate ?? '' }}"
                        data-battery-id="{{ optional(\App\Models\Battery::where('current_rider_id', $rider->id)->where('status', 'in_use')->first())->id ?? '' }}"
                        data-battery-serial="{{ optional(\App\Models\Battery::where('current_rider_id', $rider->id)->where('status', 'in_use')->first())->serial_number ?? '' }}"
                        data-wallet="{{ $rider->wallet ? number_format($rider->wallet->balance) : 0 }}"
                    >
                        {{ $rider->name }} ({{ $rider->phone }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 🛵 Motorcycle (Auto-filled) --}}
        <div class="mb-3">
            <label class="form-label">Motorcycle (Auto-detected)</label>
            <input type="text" id="motorcycleDisplay" class="form-control" readonly>
            <input type="hidden" name="motorcycle_unit_id" id="motorcycleUnitId">
        </div>

        {{-- 📍 Station --}}
        <div class="mb-3">
            <label class="form-label">Station</label>
            <div class="form-control-plaintext fw-bold">{{ Auth::user()->station->name ?? 'N/A' }}</div>
            <input type="hidden" name="station_id" value="{{ Auth::user()->station_id }}">
        </div>

        {{-- 🔋 Returned Battery --}}
        <div class="mb-3">
            <label class="form-label">Battery Returned by Rider</label>
            <select class="form-select" name="battery_returned_id" id="batteryReturnedSelect">
                <option value="">Select Rider First</option>
            </select>
        </div>

        {{-- 🔋 New Battery --}}
        <div class="mb-3">
            <label for="battery_id" class="form-label">Available Battery</label>
            <select class="form-select select2" name="battery_id" id="batterySelect" required>
                <option value="">Select Battery</option>
                @foreach($availableBatteries as $battery)
                    <option value="{{ $battery->id }}">{{ $battery->serial_number }}</option>
                @endforeach
            </select>
        </div>

        {{-- 💰 Battery Price Option --}}
        <div class="mb-3">
            <label for="base_price" class="form-label">Select Battery Price Tier</label>
            <select name="base_price" id="base_price" class="form-select" required>
                <option value="15000">Standard UGX 15,000</option>
                <option value="20000">Premium UGX 20,000</option>
            </select>
        </div>


        {{-- 🔢 Battery Percentage --}}
        <div class="mb-3">
            <label for="percentage_difference" class="form-label">Battery Percentage Returned</label>
            <input type="number" name="percentage_difference" id="battery" class="form-control" min="0" max="100" required>
            <small class="text-muted">Enter battery % returned by rider (0–100)</small>
        </div>

        {{-- 💰 Payable Amount --}}
        <div class="mb-3">
            <label class="form-label">Payable Amount (UGX)</label>
            <input type="text" id="payable_display" class="form-control" readonly>
            <input type="hidden" name="payable_amount" id="payable">
        </div>

        {{-- 💳 Payment Method --}}
        <div class="mb-4">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select">
                <option value="">None</option>
                <option value="wallet">Wallet Balance</option>
                <option value="mtn">MTN MoMo</option>
                <option value="airtel">Airtel Money</option>
                <option value="pesapal">Pesapal</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label">Rider Wallet Balance</label>
            <input type="text" id="walletBalanceDisplay" class="form-control" value="Select rider to view" readonly>
        </div>


        {{-- 🎯 Actions --}}
        <div class="d-flex justify-content-start gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i> Create Swap
            </button>
            <a href="{{ route('agent.swaps.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

{{-- 📜 JavaScript --}}
@push('scripts')
<script>
    $(document).ready(function () {
        const basePrice = {{ config('billing.base_price') ?? 15000 }};
        const batteryInput = $('#battery');
        const payableInput = $('#payable');
        const payableDisplay = $('#payable_display');
        const riderSelect = $('#riderSelect');
        const batteryReturnedSelect = $('#batteryReturnedSelect');
        const motorcycleDisplay = $('#motorcycleDisplay');
        const motorcycleUnitId = $('#motorcycleUnitId');
        const walletBalanceDisplay = $('#walletBalanceDisplay');

        // Select2 Init
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownAutoWidth: true
        });

        // 💡 Calculate Amount
        batteryInput.on('input', function () {
            const value = parseFloat($(this).val());
            const selectedBasePrice = parseFloat($('#base_price').val()); // 🆕 fetch selected price

            if (!isNaN(value) && value >= 0 && value <= 100) {
                const missing = 100 - value;
                const amount = (missing / 100) * selectedBasePrice;
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

        // 🆕 Recalculate if base price dropdown changes
        $('#base_price').on('change', function () {
            batteryInput.trigger('input');
        });


        // 🧠 Auto-fill Motorcycle & Returned Battery
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

            // ✅ Display wallet balance
            const wallet = selected.getAttribute('data-wallet');
            walletBalanceDisplay.val(wallet ? `UGX ${wallet}` : 'UGX 0');
        });
    });
</script>
@endpush
@endsection
