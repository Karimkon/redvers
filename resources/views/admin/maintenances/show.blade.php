@extends('admin.layouts.app')
@section('title', 'Maintenance Details')

@section('content')
<div class="container">
    <h4 class="mb-4">Maintenance Record Details</h4>

    <div class="card">
        <div class="card-body">
            <p><strong>Motorcycle Plate:</strong> {{ $maintenance->motorcycleUnit->number_plate ?? '—' }}</p>
            <p><strong>Mechanic:</strong> {{ $maintenance->mechanic->name ?? '—' }}</p>
            <p><strong>Reported Issue:</strong> {{ $maintenance->reported_issue }}</p>
            <p><strong>Diagnosis:</strong> {{ $maintenance->diagnosis ?? '—' }}</p>
            <p><strong>Action Taken:</strong> {{ $maintenance->action_taken ?? '—' }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-{{ $maintenance->status === 'resolved' ? 'success' : ($maintenance->status === 'in_progress' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($maintenance->status) }}
                </span>
            </p>
            <p><strong>Repair Date:</strong> {{ $maintenance->repair_date ?? '—' }}</p>
            <p><strong>Created At:</strong> {{ $maintenance->created_at->format('M d, Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
