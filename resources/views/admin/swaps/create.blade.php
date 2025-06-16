@extends('admin.layouts.app')

@section('title', 'Create Swap')

@section('content')
<div class="container-fluid">
    <div class="bg-white p-4 p-md-5 rounded shadow-sm mb-4 animate__animated animate__fadeIn">
        <h3 class="mb-4 fw-bold text-primary d-flex align-items-center">
            <i class="bi bi-lightning-charge-fill me-2 text-warning"></i> Create New Swap
        </h3>

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
            <div class="row g-3">
                {{-- Rider --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-person-circle me-1"></i> Rider</label>
                    <select name="rider_id" id="riderSelect"
                            class="form-select select2"
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

                {{-- Returned Battery --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-arrow-counterclockwise me-1"></i> Returned Battery</label>
                    <select name="battery_returned_id" class="form-select" id="batteryReturnedSelect">
                        <option value="">Select Rider First</option>
                    </select>
                    <small class="text-muted">If the rider has no previous battery, leave this empty.</small>
                </div>

                {{-- Station --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-geo-alt-fill me-1"></i> Station</label>
                    <select name="station_id" class="form-select" id="stationSelect" required>
                        <option value="">Select Station</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Agent --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-person-badge-fill me-1"></i> Agent (optional)</label>
                    <select name="agent_id" class="form-select">
                        <option value="">Select Agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Battery to Assign --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-battery me-1"></i> Battery to Assign</label>
                    <select name="battery_id" class="form-select" id="batterySelect" required>
                        <option value="">Select Station First</option>
                    </select>
                    <small class="text-muted">Only batteries in stock or charging will be shown</small>
                </div>

                {{-- Motorcycle --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-truck me-1"></i> Motorcycle (Auto-detected)</label>
                    <input type="text" id="motorcycleDisplay" class="form-control bg-light" readonly>
                    <input type="hidden" name="motorcycle_unit_id" id="motorcycleUnitId">
                </div>

                {{-- Battery Percentage --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-battery-half me-1"></i> Battery Percentage</label>
                    <input type="number" name="percentage_difference" id="battery" class="form-control" min="0" max="100" required>
                    <small class="text-muted">Enter current battery percentage (0 to 100)</small>
                </div>

                {{-- Payable Amount --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-cash-coin me-1"></i> Payable Amount (UGX)</label>
                    <input type="text" id="payable_display" class="form-control bg-light" readonly>
                    <input type="hidden" name="payable_amount" id="payable">
                </div>

                {{-- Payment Method --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="bi bi-credit-card me-1"></i> Payment Method</label>
                    <select name="payment_method" class="form-select" id="paymentMethod">
                        <option value="">None</option>
                        <option value="mtn">MTN</option>
                        <option value="airtel">Airtel</option>
                        <option value="pesapal">Pesapal</option>
                    </select>
                </div>

                <div class="col-12 text-end mt-3">
                    <button class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-arrow-right-circle me-1"></i> Submit Swap
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const basePrice = 12000;
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

    $(document).ready(function () {
        $('#riderSelect').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $('#riderSelect').data('placeholder'),
            allowClear: true
        });

        $('#batterySelect').select2({ placeholder: "Select battery..." });

        $('#riderSelect').on('change', function () {
            const selected = this.options[this.selectedIndex];
            $('#motorcycleDisplay').val(selected.getAttribute('data-bike-plate') || '❌ No motorcycle assigned');
            $('#motorcycleUnitId').val(selected.getAttribute('data-bike-id') || '');

            const batteryId = selected.getAttribute('data-battery-id');
            const batterySerial = selected.getAttribute('data-battery-serial');

            if (batteryId && batterySerial) {
                $('#batteryReturnedSelect').html(`<option value="${batteryId}" selected>${batterySerial}</option>`);
            } else {
                $('#batteryReturnedSelect').html(`<option value="">No battery found</option>`);
            }
        });

        $('#stationSelect').on('change', function () {
            const stationId = this.value;
            $('#batterySelect').html('<option>Loading...</option>');
            fetch(`/admin/api/batteries-by-station/${stationId}`)
                .then(res => res.json())
                .then(data => {
                    $('#batterySelect').html(
                        data.length
                            ? data.map(b => `<option value="${b.id}">${b.serial_number}</option>`).join('')
                            : '<option value="">No batteries available</option>'
                    ).trigger('change');
                })
                .catch(() => {
                    $('#batterySelect').html('<option value="">Failed to load batteries</option>');
                });
        });
    });

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

    form.addEventListener('submit', function (e) {
        if (!motorcycleUnitId.value) {
            e.preventDefault();
            alert('⚠️ No motorcycle assigned to this rider.');
        }
    });
</script>
@endpush
