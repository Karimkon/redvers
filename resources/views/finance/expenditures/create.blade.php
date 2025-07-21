@extends('finance.layouts.app')

@section('title', 'Add Expenditure')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">➕ Record New Expenditure</h4>

    <form action="{{ route('finance.expenditures.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Amount (UGX)</label>
                <input type="number" name="amount" class="form-control" step="0.01" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select" required>
                    <option value="bank">Bank</option>
                    <option value="petty_cash">Petty Cash</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description (optional)</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Save Expenditure</button>
        </div>
    </form>
</div>
@endsection
