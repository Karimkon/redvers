@extends('agent.layouts.app')

@section('title', 'Initiate Daily Payment')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <h4 class="fw-bold text-primary mb-4">
        <i class="bi bi-cash-coin me-2"></i> Initiate Daily Motorcycle Payment (UGX 12,000)
    </h4>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('agent.daily-payments.store') }}" class="card p-4 shadow-sm border-0" id="paymentForm">
        @csrf

        {{-- Rider Dropdown --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Select Rider</label>
            <select class="form-select select2" name="rider_id" required id="riderSelect">
                <option value="">-- Choose Rider --</option>
                @foreach($riders as $rider)
                    <option value="{{ $rider->id }}" 
                            data-purchase-id="{{ $rider->purchases->first()->id ?? '' }}"
                            data-phone="{{ $rider->phone }}">
                        {{ $rider->name }} - {{ $rider->phone }}
                    </option>

                @endforeach
            </select>
        </div>

        {{-- Payment Amount --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Payment Amount (UGX)</label>
            <select class="form-select" name="amount" required>
                <option value="12000" selected>UGX 12,000 (Default)</option>
                <option value="13000">UGX 13,000</option>
                <option value="16000">UGX 16,000</option>
            </select>
        </div>

        {{-- Mobile Money Number --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Mobile Money Number to Pay From</label>
            <input type="tel" class="form-control" name="phone_number" 
                   placeholder="e.g., 0777123456"
                   pattern="[0-9]{9,10}">
            <small class="text-muted">Format: 0777123456 or 256777123456</small>
        </div>

        <input type="hidden" name="payment_method" value="pesapal">

        <button type="submit" class="btn btn-success" id="submitBtn">
            <i class="bi bi-credit-card me-1"></i> Proceed to Payment
            <span id="spinner" class="spinner-border spinner-border-sm d-none"></span>
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#riderSelect').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Search rider by name or phone',
            allowClear: true
        });

        // Autofill phone number when rider is selected
        $('#riderSelect').on('change', function () {
            const phone = $(this).find('option:selected').data('phone');
            if (phone) {
                $('input[name="phone_number"]').val(phone);
            }
        });
        
        $('#paymentForm').on('submit', function() {
            $('#submitBtn').prop('disabled', true);
            $('#spinner').removeClass('d-none');
        });
    });
</script>
@endpush