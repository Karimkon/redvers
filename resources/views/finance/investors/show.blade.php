@extends('finance.layouts.app')

@section('title', 'Investor Details')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-4">Investor: {{ $investor->name }}</h4>

        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Email:</strong> {{ $investor->email ?? 'N/A' }}</li>
            <li class="list-group-item"><strong>Phone:</strong> {{ $investor->phone ?? 'N/A' }}</li>
            <li class="list-group-item"><strong>Contribution:</strong> UGX {{ number_format($investor->contribution) }}</li>
            <li class="list-group-item"><strong>Ownership %:</strong> {{ $investor->ownership_percentage ?? 'N/A' }}</li>
            <li class="list-group-item"><strong>Payment Method:</strong> {{ ucfirst($investor->payment_method) }}</li>
            <li class="list-group-item"><strong>Date:</strong> {{ \Carbon\Carbon::parse($investor->date)->format('d M Y') }}</li>
            <li class="list-group-item"><strong>Attachment:</strong>
                @if($investor->attachment_id)
                    <li class="list-group-item"><strong>Attachment:</strong>
                    @if($investor->attachment_id)
                        <a href="{{ asset('storage/' . $investor->attachment->file_path) }}" target="_blank">📎 View File</a>

                    @else
                        <em>No document</em>
                    @endif
                    </li>

                @else
                    <em>No document</em>
                @endif
            </li>
        </ul>

        <div class="mt-4 text-end">
            <a href="{{ route('finance.investors.edit', $investor) }}" class="btn btn-warning">✏️ Edit</a>
            <a href="{{ route('finance.investors.index') }}" class="btn btn-secondary">⬅️ Back to List</a>
        </div>
    </div>
</div>
@endsection
