@extends('admin.layouts.app')

@section('title', 'View Inventory Operator')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Operator Details</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $inventory->name }}</p>
            <p><strong>Phone:</strong> {{ $inventory->phone }}</p>
            <p><strong>Email:</strong> {{ $inventory->email ?? '—' }}</p>
            <p><strong>Assigned Shop:</strong> {{ $inventory->shop?->name ?? 'N/A' }}</p>
        </div>
    </div>
</div>
@endsection
