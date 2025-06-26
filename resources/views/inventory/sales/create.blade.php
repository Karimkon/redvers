@extends('inventory.layouts.app')

@section('title', 'Record Sale')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Record New Sale</h4>

    <form method="POST" action="{{ route('inventory.sales.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Part</label>
                <select name="part_id" id="part_id" class="form-select select2" required>
                    <option value="" disabled selected>-- Select Part --</option>
                    @foreach($parts as $part)
                        <option value="{{ $part->id }}">{{ $part->name }} (In Stock: {{ $part->stock }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Quantity Sold</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Selling Price (UGX)</label>
                <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Total Price (UGX)</label>
                <input type="text" id="total_price" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Customer Name (optional)</label>
                <input type="text" name="customer_name" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Sale Date</label>
                <input type="date" name="sold_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Sale</button>
            <a href="{{ route('inventory.sales.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const partsData = @json($parts->mapWithKeys(fn($part) => [$part->id => $part->price]));

    document.addEventListener('DOMContentLoaded', () => {
        const priceInput = document.getElementById('selling_price');
        const quantityInput = document.getElementById('quantity');
        const totalPriceInput = document.getElementById('total_price');

        function updateTotal() {
            const price = parseFloat(priceInput.value) || 0;
            const qty = parseInt(quantityInput.value) || 0;
            totalPriceInput.value = (price * qty).toLocaleString('en-UG', {
                maximumFractionDigits: 2
            });
        }

        // Initialize Select2
        const $partSelect = $('#part_id').select2({
            placeholder: 'Search and select a part',
            width: '100%'
        });

        // Auto-fill price based on selected part
        $partSelect.on('change', function () {
            const partId = $(this).val();
            if (partsData[partId]) {
                priceInput.value = partsData[partId];
                updateTotal();
            }
        });

        priceInput.addEventListener('input', updateTotal);
        quantityInput.addEventListener('input', updateTotal);
    });
</script>
@endpush

