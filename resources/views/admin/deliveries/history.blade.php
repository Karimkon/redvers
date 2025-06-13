@extends('admin.layouts.app')

@section('title', 'Returned Battery History')

@section('content')
<div class="container">
    <h3 class="mb-4">Battery Return History</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="returnHistoryTable" class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Battery</th>
                        <th>Station</th>
                        <th>Delivered By</th>
                        <th>Agent</th>
                        <th>Received At</th>
                        <th>Returned At</th>
                        <th>Returned By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $index => $delivery)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($delivery->battery)->serial_number ?? 'N/A' }}</td>
                            <td>{{ optional($delivery->station)->name ?? 'N/A' }}</td>
                            <td>{{ $delivery->delivered_by ?? 'N/A' }}</td>
                            <td>{{ optional($delivery->agent)->name ?? 'N/A' }}</td>
                            <td>{{ optional($delivery->received_at)->format('d-M-Y H:i') ?? '-' }}</td>
                            <td>{{ optional($delivery->returned_at)->format('d-M-Y H:i') ?? '-' }}</td>
                            <td>{{ optional($delivery->returnedByAdmin)->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No return records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#returnHistoryTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true
        });
    });
</script>
@endpush

@endsection
