@extends('finance.layouts.app')

@section('title', 'Tax Record Details')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-4">Tax: {{ $tax->type }}</h4>

        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Amount:</strong> UGX {{ number_format($tax->amount) }}</li>
            <li class="list-group-item"><strong>Date:</strong> {{ \Carbon\Carbon::parse($tax->date)->format('d M Y') }}</li>
            <li class="list-group-item"><strong>Method:</strong> {{ ucfirst($tax->payment_method) }}</li>
            <li class="list-group-item"><strong>Description:</strong> {{ $tax->description ?? 'N/A' }}</li>
            <li class="list-group-item"><strong>Attachment:</strong>
                @if($tax->attachment_id && $tax->attachment)
                    <a href="{{ asset('storage/' . $tax->attachment->file_path) }}" target="_blank">📎 View File</a>
                @else
                    <em>No document</em>
                @endif
            </li>

        </ul>

        <div class="mt-4 text-end">
            <a href="{{ route('finance.taxes.edit', $tax) }}" class="btn btn-warning">✏️ Edit</a>
            <a href="{{ route('finance.taxes.index') }}" class="btn btn-secondary">⬅️ Back to List</a>
        </div>
    </div>
</div>
@endsection
