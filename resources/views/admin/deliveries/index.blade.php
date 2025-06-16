@extends('admin.layouts.app')

@section('title', 'All Battery Deliveries')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-truck-front-fill me-2 text-primary"></i> Battery Deliveries Overview</h3>
        <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> New Delivery
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th><i class="bi bi-battery"></i> Battery Serial</th>
                            <th><i class="bi bi-person"></i> Delivered To</th>
                            <th><i class="bi bi-geo-alt-fill"></i> Station</th>
                            <th><i class="bi bi-person-circle"></i> Delivered By</th>
                            <th><i class="bi bi-check-circle"></i> Received?</th>
                            <th><i class="bi bi-clock-history"></i> Received At</th>
                            <th><i class="bi bi-hash"></i> Batch Code</th>
                            <th><i class="bi bi-calendar-event"></i> Date</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($deliveries as $delivery)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $delivery->battery->serial_number ?? 'N/A' }}</td>
                                <td>{{ $delivery->agent->name ?? 'N/A' }}</td>
                                <td>{{ $delivery->station->name ?? 'N/A' }}</td>
                                <td>{{ $delivery->delivered_by ?? '-' }}</td>
                                <td>
                                    @if($delivery->received)
                                        <span class="badge bg-success px-3 py-1">Yes</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-1">No</span>
                                    @endif
                                </td>
                                <td>{{ $delivery->received_at ? $delivery->received_at->format('d-M-Y H:i') : '-' }}</td>
                                <td class="text-uppercase text-muted">{{ $delivery->delivery_code }}</td>
                                <td>{{ $delivery->created_at->format('d-M-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-archive-fill fs-4"></i><br>
                                    No deliveries recorded yet.
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
