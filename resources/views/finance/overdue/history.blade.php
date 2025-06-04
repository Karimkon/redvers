@extends('finance.layouts.app')

@section('title', 'Follow-Up History')

@section('content')
<div class="container">
    <h4 class="mb-4">
        <i class="bi bi-clock-history"></i> Follow-Up History
        <a href="{{ route('finance.overdue.index') }}" class="btn btn-secondary btn-sm float-end">← Back</a>
    </h4>

    @if($followups->count())
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Missed Date</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($followups as $index => $followup)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($followup->missed_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $followup->status === 'contacted' ? 'success' : 
                                        ($followup->status === 'resolved' ? 'primary' : 'warning') 
                                    }}">
                                        {{ ucfirst($followup->status) }}
                                    </span>
                                </td>
                                <td>{{ $followup->note ?? '—' }}</td>
                                <td>{{ $followup->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No follow-up history available for this rider yet.
        </div>
    @endif
</div>
@endsection
