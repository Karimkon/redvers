@extends('finance.layouts.app')

@section('title', 'COGS Detail')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">📄 COGS Details</h4>

    <div class="card shadow-sm p-4">
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>Product:</strong> {{ $cogs->product }}
            </div>
            <div class="col-md-6">
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($cogs->date)->format('d M Y') }}
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4">
                <strong>Unit Cost:</strong> UGX {{ number_format($cogs->unit_cost) }}
            </div>
            <div class="col-md-4">
                <strong>Quantity:</strong> {{ $cogs->quantity }}
            </div>
            <div class="col-md-4">
                <strong>Total Cost:</strong> UGX {{ number_format($cogs->unit_cost * $cogs->quantity) }}
            </div>
        </div>

        @if($cogs->description)
        <div class="mb-2">
            <strong>Description:</strong>
            <p>{{ $cogs->description }}</p>
        </div>
        @endif

        @if($cogs->attachment)
        <div class="mb-2">
            <strong>Attachment:</strong>
            <a href="{{ asset('storage/' . $cogs->attachment->file_path) }}" target="_blank">View File</a>
        </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('finance.cogs.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('finance.cogs.edit', $cogs) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection
