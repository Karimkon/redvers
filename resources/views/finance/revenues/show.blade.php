@extends('finance.layouts.app')

@section('title', 'Revenue Details')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-4">Revenue: {{ $revenue->source }}</h4>

        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Amount:</strong> UGX {{ number_format($revenue->amount) }}</li>
            <li class="list-group-item"><strong>Date:</strong> {{ \Carbon\Carbon::parse($revenue->date)->format('d M Y') }}</li>
            <li class="list-group-item"><strong>Method:</strong> {{ ucfirst($revenue->payment_method) }}</li>
            <li class="list-group-item"><strong>Reference:</strong> {{ $revenue->reference ?? 'N/A' }}</li>
            <li class="list-group-item"><strong>Description:</strong> {{ $revenue->description ?? 'N/A' }}</li>
            <li class="list-group-item"><strong>Attachment:</strong>
                @if($revenue->attachment_id)
                    <a href="{{ route('attachments.show', $revenue->attachment_id) }}" target="_blank">📎 View File</a>
                @else
                    <em>No document</em>
                @endif
            </li>
        </ul>

        <div class="mt-4 text-end">
            <a href="{{ route('finance.revenues.edit', $revenue) }}" class="btn btn-warning">✏️ Edit</a>
            <a href="{{ route('finance.revenues.index') }}" class="btn btn-secondary">⬅️ Back to List</a>
        </div>
    </div>
</div>
@endsection
