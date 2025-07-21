@extends('finance.layouts.app')

@section('title', 'Expenditure Details')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">📄 Expenditure Details</h4>

    <div class="card p-4 shadow-sm">
        <div class="mb-3">
            <strong>Category:</strong> {{ $expenditure->category }}
        </div>

        <div class="mb-3">
            <strong>Amount:</strong> UGX {{ number_format($expenditure->amount) }}
        </div>

        <div class="mb-3">
            <strong>Payment Method:</strong> {{ ucfirst($expenditure->payment_method) }}
        </div>

        <div class="mb-3">
            <strong>Date:</strong> {{ \Carbon\Carbon::parse($expenditure->date)->format('d M Y') }}
        </div>

        @if($expenditure->description)
        <div class="mb-3">
            <strong>Description:</strong> {{ $expenditure->description }}
        </div>
        @endif

        @if($expenditure->attachment)
        <div class="mb-3">
            <strong>Attachment:</strong>
            <a href="{{ asset('storage/' . $expenditure->attachment->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                View Receipt
            </a>
        </div>
        @endif

        <a href="{{ route('finance.expenditures.index') }}" class="btn btn-secondary">← Back to List</a>
    </div>
</div>
@endsection
