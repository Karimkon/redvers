@extends('finance.layouts.app')

@section('title', 'Edit Expenditure')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">✏️ Edit Expenditure</h4>

    <form action="{{ route('finance.expenditures.update', $expenditure) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $expenditure->category) }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Amount (UGX)</label>
                <input type="number" name="amount" class="form-control" step="0.01" value="{{ old('amount', $expenditure->amount) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select" required>
                    <option value="bank" {{ $expenditure->payment_method == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="petty_cash" {{ $expenditure->payment_method == 'petty_cash' ? 'selected' : '' }}>Petty Cash</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', \Carbon\Carbon::parse($expenditure->date)->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description (optional)</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description', $expenditure->description) }}</textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update Expenditure</button>
        </div>
    </form>
</div>
@endsection
