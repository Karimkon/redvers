@extends('finance.layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-5">
    <h2 class="mb-4">📊 Overdue Riders</h2>

    {{-- 🔍 Filters --}}
    <form method="GET" action="{{ route('finance.overdue.index') }}" class="row gy-2 gx-3 align-items-end mb-4">
        <div class="col-12 col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">-- All --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
            </select>
        </div>

        <div class="col-12 col-md-4">
            <label class="form-label">Min Missed Days</label>
            <input type="number" name="min_days" class="form-control" value="{{ request('min_days') }}">
        </div>

        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('finance.overdue.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
        </div>
    </form>

    {{-- ℹ️ Filter Message --}}
    @if ($statusFilter || $minMissedDays)
        <div class="alert alert-info">
            <strong>Filtering:</strong>
            {{ $statusFilter ? "Status: $statusFilter" : '' }}
            {{ $minMissedDays ? " | Minimum Missed Days: $minMissedDays" : '' }}
        </div>
    @endif

    {{-- 📋 Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Missed Days</th>
                    <th>Expected Payment</th>
                    <th>Follow-Up Status</th>
                    <th>Contacted at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($overdueRiders as $rider)
                    <tr>
                        <td>{{ $rider->name }}</td>
                        <td>{{ $rider->phone }}</td>
                        <td>{{ $rider->missed_days }}</td>
                        <td>UGX {{ number_format($rider->due_amount ?? 0) }}</td>
                        <td>
                            @if ($rider->latest_followup)
                                <span class="badge bg-info">{{ ucfirst($rider->latest_followup->status) }}</span>
                                <small class="d-block text-muted">{{ \Carbon\Carbon::parse($rider->latest_followup->created_at)->diffForHumans() }}</small>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ $rider->latest_followup->contacted_at ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('finance.followup.mark', $rider->purchase_id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="missed_date" value="{{ now()->toDateString() }}">
                                <button class="btn btn-sm btn-success mb-1">Mark as Contacted</button>
                            </form>
                            <a href="{{ route('finance.followup.history', ['purchase' => $rider->purchase_id]) }}" class="btn btn-sm btn-secondary">History</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">✅ All riders are currently up to date.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
