@extends('mechanic.layouts.app')
@section('title', 'Maintenance History')

@section('content')
<div class="container">
    <h4 class="mb-3">My Maintenance Logs</h4>

    <a href="{{ route('mechanic.maintenances.create') }}" class="btn btn-primary mb-3">
        + New Maintenance Entry
    </a>

    <div class="table-responsive">
        <form method="GET" class="row mb-3">
    <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by number plate, name, email, or phone">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Search
        </button>
    </div>
</form>

        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Motorcycle</th>
                    <th>Issue</th>
                    <th>Status</th>
                    <th>Repaired On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($repairs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->motorcycleUnit->number_plate ?? 'N/A' }}</td>
                        <td>{{ Str::limit($log->reported_issue, 40) }}</td>
                        <td><span class="badge bg-{{ $log->status === 'resolved' ? 'success' : ($log->status === 'in_progress' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($log->status) }}</span></td>
                        <td>{{ $log->repair_date ?? '—' }}</td>
                        <td>
                            <a href="{{ route('mechanic.maintenances.show', $log->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('mechanic.maintenances.edit', $log->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No maintenance logs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $repairs->links() }}
</div>
@endsection
