@extends('mechanic.layouts.app')
@section('title', 'Edit Maintenance')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Maintenance Record</h4>

    <form method="POST" action="{{ route('mechanic.maintenances.update', $maintenance->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Reported Issue</label>
            <textarea name="reported_issue" class="form-control" required>{{ $maintenance->reported_issue }}</textarea>
        </div>

        <div class="mb-3">
            <label>Diagnosis</label>
            <textarea name="diagnosis" class="form-control">{{ $maintenance->diagnosis }}</textarea>
        </div>

        <div class="mb-3">
            <label>Action Taken</label>
            <textarea name="action_taken" class="form-control">{{ $maintenance->action_taken }}</textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $maintenance->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $maintenance->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ $maintenance->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Repair Date</label>
            <input type="date" name="repair_date" class="form-control" value="{{ $maintenance->repair_date }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
