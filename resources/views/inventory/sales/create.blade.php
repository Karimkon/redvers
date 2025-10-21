@extends('inventory.layouts.app')

@section('title', 'Record Sale')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Record New Sale</h4>

    {{-- Display Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('inventory.sales.store') }}" id="saleForm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Part *</label>
                <select name="part_id" id="part_id" class="form-select select2" required>
                    <option value="" disabled selected>-- Select Part --</option>
                    @foreach($parts as $part)
                        <option value="{{ $part->id }}" data-price="{{ $part->price }}">
                            {{ $part->name }} (Stock: {{ $part->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Payment Method *</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="cash" selected>Cash</option>
                    <option value="pesapal">Online (Pesapal)</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Quantity Sold *</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Selling Price (UGX) *</label>
                <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Total Price (UGX)</label>
                <input type="text" id="total_price" class="form-control bg-light" readonly>
                <input type="hidden" id="total_amount" name="total_amount">
            </div>

            <div class="col-md-6">
                <label class="form-label">Customer Name (optional)</label>
                <input type="text" name="customer_name" class="form-control" placeholder="Walk-in Customer">
            </div>

            <div class="col-md-6">
                <label class="form-label">Sale Date *</label>
                <input type="date" name="sold_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <span class="btn-text">Save Sale</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
            <a href="{{ route('inventory.sales.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const partSelect = document.getElementById('part_id');
    const paymentMethod = document.getElementById('payment_method');
    const priceInput = document.getElementById('selling_price');
    const quantityInput = document.getElementById('quantity');
    const totalPriceInput = document.getElementById('total_price');
    const totalAmountInput = document.getElementById('total_amount');
    const form = document.getElementById('saleForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    function updateTotal() {
        const price = parseFloat(priceInput.value) || 0;
        const qty = parseInt(quantityInput.value) || 0;
        const total = price * qty;
        
        totalPriceInput.value = total.toLocaleString('en-UG', {
            style: 'currency',
            currency: 'UGX',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        totalAmountInput.value = total;
    }

    // Initialize Select2
    $(partSelect).select2({
        placeholder: 'Search and select a part',
        width: '100%'
    });

    // Auto-fill price based on selected part
    $(partSelect).on('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const partPrice = selectedOption.getAttribute('data-price');
        
        if (partPrice) {
            priceInput.value = partPrice;
            updateTotal();
        }
    });

    // Update total when price or quantity changes
    priceInput.addEventListener('input', updateTotal);
    quantityInput.addEventListener('input', updateTotal);

    // Handle form submission for Pesapal payments
    form.addEventListener('submit', function(e) {
        const selectedPayment = paymentMethod.value;
        
        if (selectedPayment === 'pesapal') {
            // Show loading state
            btnText.textContent = 'Redirecting to Payment...';
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;
            
            // Allow the form to submit normally - the backend will handle the redirect
            // Don't prevent default
        } else {
            // For cash payments, just show normal loading
            btnText.textContent = 'Processing...';
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;
        }
    });

    // Initialize total on page load
    updateTotal();
});
</script>
@endpush