@extends('finance.layouts.app')

@section('title', 'Edit Depreciation')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4 text-primary">✏️ Edit Depreciation</h4>

    <form method="POST" action="{{ route('finance.depreciations.update', $depreciation) }}" class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

        {{-- Product Dropdown --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Product</label>
            <select name="product_id" class="form-select" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $depreciation->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Initial Value --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Initial Value (UGX)</label>
            <input type="number" step="0.01" name="initial_value" id="initial_value" class="form-control"
                   value="{{ old('initial_value', $depreciation->initial_value) }}" required>
        </div>

        {{-- Depreciation Rate --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Depreciation Rate (%)</label>
            <input type="number" step="0.01" name="depreciation_rate" id="depreciation_rate" class="form-control"
                   value="{{ old('depreciation_rate', $depreciation->depreciation_rate) }}" required>
        </div>

        {{-- Lifespan --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Lifespan (months)</label>
            <input type="number" name="lifespan_months" id="lifespan_months" class="form-control"
                   value="{{ old('lifespan_months', $depreciation->lifespan_months) }}">
        </div>

        {{-- Start Date --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Start Date</label>
            <input type="date" name="start_date" class="form-control"
                   value="{{ old('start_date', $depreciation->start_date->format('Y-m-d')) }}" required>
        </div>

        {{-- Note --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Note</label>
            <textarea name="note" class="form-control">{{ old('note', $depreciation->note) }}</textarea>
        </div>

        {{-- 📊 Depreciation Preview --}}
        <div class="mb-4 p-3 rounded border bg-light" id="depreciationPreview" style="display: none;">
            <h6 class="fw-bold mb-2 text-dark">📊 Depreciation Preview</h6>
            <ul class="mb-0">
                <li><strong>Monthly Depreciation:</strong> <span id="monthlyDepreciation">—</span> UGX</li>
                <li><strong>Total Depreciation:</strong> <span id="totalDepreciation">—</span> UGX</li>
                <li><strong>Final Value:</strong> <span id="finalValue">—</span> UGX</li>
            </ul>
        </div>

        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function calculateDepreciation() {
        const initialValue = parseFloat(document.getElementById('initial_value').value) || 0;
        const rate = parseFloat(document.getElementById('depreciation_rate').value) || 0;
        const lifespan = parseInt(document.getElementById('lifespan_months').value) || 0;

        if (initialValue > 0 && rate > 0 && lifespan > 0) {
            const totalDepreciation = (initialValue * (rate / 100));
            const monthlyDepreciation = (totalDepreciation / lifespan);
            const finalValue = initialValue - totalDepreciation;

            document.getElementById('monthlyDepreciation').textContent = monthlyDepreciation.toFixed(2);
            document.getElementById('totalDepreciation').textContent = totalDepreciation.toFixed(2);
            document.getElementById('finalValue').textContent = finalValue.toFixed(2);
            document.getElementById('depreciationPreview').style.display = 'block';
        } else {
            document.getElementById('depreciationPreview').style.display = 'none';
        }
    }

    document.getElementById('initial_value').addEventListener('input', calculateDepreciation);
    document.getElementById('depreciation_rate').addEventListener('input', calculateDepreciation);
    document.getElementById('lifespan_months').addEventListener('input', calculateDepreciation);

    // Trigger preview on load
    window.addEventListener('DOMContentLoaded', calculateDepreciation);
</script>
@endsection
