@extends('mechanic.layouts.app')
@section('title', 'Maintenance Details')

@section('content')
<div class="container">
    <h4 class="mb-3">Maintenance Details</h4>

    <div class="card">
        <div class="card-body">
            <p><strong>Motorcycle Unit:</strong> {{ $maintenance->motorcycleUnit->number_plate ?? '—' }}</p>
            <p><strong>Issue:</strong> {{ $maintenance->reported_issue }}</p>
            <p><strong>Diagnosis:</strong> {{ $maintenance->diagnosis ?? '—' }}</p>
            <p><strong>Solution:</strong> {{ $maintenance->action_taken ?? '—' }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ $maintenance->status === 'resolved' ? 'success' : ($maintenance->status === 'in_progress' ? 'warning' : 'secondary') }}">{{ ucfirst($maintenance->status) }}</span></p>
            <p><strong>Repair Date:</strong> {{ $maintenance->repair_date ?? '—' }}</p>
        </div>
    </div>

    <a href="{{ route('mechanic.maintenances.edit', $maintenance->id) }}" class="btn btn-warning mt-3">Edit</a>
</div>
@endsection
