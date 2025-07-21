@extends('finance.layouts.app')

@section('title', 'Edit Loan')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">✏️ Edit Loan</h4>

    <form action="{{ route('finance.loans.update', $loan) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Lender</label>
            <input type="text" name="lender" class="form-control" value="{{ old('lender', $loan->lender) }}" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Amount (UGX)</label>
                <input type="number" name="amount" step="0.01" class="form-control" value="{{ $loan->amount }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Interest Rate (%)</label>
                <input type="number" name="interest_rate" step="0.01" class="form-control" value="{{ $loan->interest_rate }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Issued Date</label>
                <input type="date" name="issued_date" class="form-control" value="{{ $loan->issued_date->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" value="{{ $loan->due_date->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ $loan->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ $loan->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="defaulted" {{ $loan->status == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Interest Paid (UGX)</label>
            <input type="number" name="interest_paid" class="form-control" step="0.01" value="{{ $loan->interest_paid }}">
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update Loan</button>
        </div>
    </form>
</div>
@endsection
