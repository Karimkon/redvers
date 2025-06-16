@extends('admin.layouts.app')

@section('title', 'Returned Battery History')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            <i class="bi bi-clock-history me-2 text-info"></i> Returned Battery History
        </h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="returnHistoryTable" class="table table-bordered table-striped align-middle w-100">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th><i class="bi bi-battery-full"></i> Battery</th>
                            <th><i class="bi bi-geo-alt-fill"></i> Station</th>
                            <th><i class="bi bi-truck"></i> Delivered By</th>
                            <th><i class="bi bi-person-fill"></i> Agent</th>
                            <th><i class="bi bi-check2-square"></i> Received At</th>
                            <th><i class="bi bi-arrow-return-left"></i> Returned At</th>
                            <th><i class="bi bi-person-workspace"></i> Returned By</th>
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
                                <td colspan="8" class="text-center text-muted">
                                    <i class="bi bi-info-circle"></i> No return records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables and Bootstrap JS (if not already included) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#returnHistoryTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            language: {
                search: "🔍 Search history:",
                emptyTable: "No history data available",
                zeroRecords: "Nothing found matching your search"
            }
        });
    });
</script>
@endpush
