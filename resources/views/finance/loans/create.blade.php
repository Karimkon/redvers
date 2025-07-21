@extends('finance.layouts.app')

@section('title', 'Record New Loan')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">➕ Record New Loan</h4>

    <form action="{{ route('finance.loans.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Lender</label>
            <input type="text" name="lender" class="form-control" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Amount (UGX)</label>
                <input type="number" name="amount" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Interest Rate (%)</label>
                <input type="number" name="interest_rate" step="0.01" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Issued Date</label>
                <input type="date" name="issued_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="defaulted">Defaulted</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Interest Paid (UGX)</label>
            <input type="number" name="interest_paid" class="form-control" step="0.01">
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Save Loan</button>
        </div>
    </form>
</div>
@endsection
