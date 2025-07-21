@extends('finance.layouts.app')

@section('title', 'Loan Details')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">📄 Loan Details</h4>

    <div class="card p-4 shadow-sm">
        <p><strong>Lender:</strong> {{ $loan->lender }}</p>
        <p><strong>Amount:</strong> UGX {{ number_format($loan->amount) }}</p>
        <p><strong>Interest Rate:</strong> {{ $loan->interest_rate }}%</p>
        <p><strong>Interest Paid:</strong> UGX {{ number_format($loan->interest_paid) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($loan->status) }}</p>
        <p><strong>Issued Date:</strong> {{ $loan->issued_date->format('d M Y') }}</p>
        <p><strong>Due Date:</strong> {{ $loan->due_date->format('d M Y') }}</p>

        @if($loan->attachment)
        <p>
            <strong>Attachment:</strong>
            <a href="{{ asset('storage/' . $loan->attachment->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">View Document</a>
        </p>
        @endif

        <a href="{{ route('finance.loans.index') }}" class="btn btn-secondary mt-3">← Back to List</a>
    </div>
</div>
@endsection
