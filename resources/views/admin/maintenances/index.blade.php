@extends('admin.layouts.app')
@section('title', 'Maintenance Records')

@section('content')
<div class="container">
    <h4 class="mb-3">All Maintenance Logs</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Bike Plate</th>
                    <th>Reported Issue</th>
                    <th>Mechanic</th>
                    <th>Status</th>
                    <th>Repaired On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->motorcycleUnit->number_plate ?? 'N/A' }}</td>
                        <td>{{ Str::limit($log->reported_issue, 40) }}</td>
                        <td>{{ $log->mechanic->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $log->status === 'resolved' ? 'success' : ($log->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td>{{ $log->repair_date ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.maintenance.show', $log->id) }}" class="btn btn-sm btn-info">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No maintenance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $records->links() }}
</div>
@endsection
