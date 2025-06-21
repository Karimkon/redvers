@extends('agent.layouts.app')

@section('title', 'Initiate Daily Payment')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <h4 class="fw-bold text-primary mb-4">
        <i class="bi bi-cash-coin me-2"></i> Initiate Daily Motorcycle Payment (UGX 12,000)
    </h4>

    <form method="POST" action="{{ route('agent.daily-payments.store') }}" class="card p-4 shadow-sm border-0">
        @csrf

        {{-- Rider Dropdown --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Select Rider</label>
            <select class="form-select select2" name="rider_id" required id="riderSelect">
                <option value="">-- Choose Rider --</option>
                @foreach($riders as $rider)
                    <option value="{{ $rider->id }}" data-purchase-id="{{ $rider->purchases->first()->id ?? '' }}">
                        {{ $rider->name }} - {{ $rider->phone }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- 💰 Payment Amount Dropdown --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Payment Amount (UGX)</label>
            <select class="form-select" name="amount" required>
                <option value="12000" selected>UGX 12,000 (Default)</option>
                <option value="13000">UGX 13,000</option>
                <option value="16000">UGX 16,000</option>
            </select>
        </div>


        {{-- Purchase ID (Hidden) --}}
        <input type="hidden" name="purchase_id" id="purchaseInput">

        {{-- Payment Method --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Payment Method</label>
            <select class="form-select" name="payment_method" required>
                <option value="pesapal">Pesapal</option>
            </select>
        </div>

        <button class="btn btn-success">
            <i class="bi bi-credit-card me-1"></i> Proceed to Payment
        </button>
    </form>
</div>
@endsection

@push('scripts')
{{-- Enable Select2 --}}
<script>
    $(document).ready(function () {
        $('#riderSelect').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Search rider by name or phone',
            allowClear: true
        });

        $('#riderSelect').on('change', function () {
            const selected = this.options[this.selectedIndex];
            $('#purchaseInput').val($(selected).data('purchase-id'));
        });
    });
</script>
@endpush
