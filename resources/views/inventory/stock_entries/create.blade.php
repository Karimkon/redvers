<!-- resources/views/inventory/stock_entries/create.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Add Stock Entry')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Receive New Stock</h4>

    <form method="POST" action="{{ route('inventory.stock-entries.store') }}">
        @csrf
        <div class="row g-3">
            <!-- 🔍 Searchable Parts Dropdown -->
            <div class="col-md-6">
                <label class="form-label">Part</label>
                <select name="part_id" id="part_id" class="form-select" required>
                    <option value="" disabled selected>-- Select Part --</option>
                    @foreach($parts as $part)
                        <option value="{{ $part->id }}">
                            {{ $part->name }} (Stock: {{ $part->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Quantity Received</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Cost Price (UGX)</label>
                <input type="number" step="0.01" name="cost_price" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Received Date</label>
                <input type="date" name="received_at" class="form-control" 
                       value="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save me-1"></i> Save Entry
            </button>
            <a href="{{ route('inventory.stock-entries.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#part_id').select2({
                placeholder: "-- Select Part --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
