@extends('finance.layouts.app')

@section('title', 'Edit COGS Entry')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">✏️ Edit COGS Entry</h4>

    <form action="{{ route('finance.cogs.update', $cogs) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <select name="product_id" class="form-select" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id', $cogs->product_id) == $product->id)>
                        {{ $product->name }} - UGX {{ number_format($product->unit_cost) }}
                    </option>
                @endforeach
            </select>

        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Unit Cost (UGX)</label>
                <input type="number" name="unit_cost" class="form-control" step="0.01" value="{{ old('unit_cost', $cogs->unit_cost) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $cogs->quantity) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', $cogs->date->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description', $cogs->description) }}</textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update Entry</button>
        </div>
    </form>
</div>
@endsection
