<!-- resources/views/inventory/stock_entries/create.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Add Stock Entry')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Receive New Stock</h4>

    <form method="POST" action="{{ route('inventory.stock-entries.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Part</label>
                <select name="part_id" class="form-select" required>
                    <option value="" disabled selected>-- Select Part --</option>
                    @foreach($parts as $part)
                        <option value="{{ $part->id }}">{{ $part->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Quantity Received</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Cost Price (UGX)</label>
                <input type="number" step="0.01" name="cost_price" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Received Date</label>
                <input type="date" name="received_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Save Entry</button>
            <a href="{{ route('inventory.stock-entries.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
