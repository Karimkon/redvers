@extends('finance.layouts.app')

@section('title', 'Add COGS Entry')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">➕ Add New COGS Entry</h4>

    <form action="{{ route('finance.cogs.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category_id" id="category_id" class="form-select" required onchange="filterProducts()">
        <option value="">-- Select Category --</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Product Name</label>
    <select name="product_id" id="product_id" class="form-select" required>
        <option value="">-- Select Product --</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}" data-category="{{ $product->category_id }}">
                {{ $product->name }} - UGX {{ number_format($product->unit_cost) }}
            </option>
        @endforeach
    </select>
</div>


        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Unit Cost (UGX)</label>
                <input type="number" name="unit_cost" class="form-control" step="0.01" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description (Optional)</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Save Entry</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function filterProducts() {
        let categoryId = document.getElementById('category_id').value;
        let productSelect = document.getElementById('product_id');
        let options = productSelect.querySelectorAll('option');

        options.forEach(option => {
            if (!option.dataset.category) return; // skip placeholder
            option.style.display = option.dataset.category === categoryId ? 'block' : 'none';
        });

        productSelect.value = '';
    }

    // Optional: filter once on load if category is pre-selected
    document.addEventListener('DOMContentLoaded', filterProducts);
</script>
@endpush

