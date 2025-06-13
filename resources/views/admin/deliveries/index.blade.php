@extends('admin.layouts.app')

@section('title', 'All Battery Deliveries')

@section('content')
<div class="container">
    <h3 class="mb-4">Battery Deliveries Overview</h3>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Delivery
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Battery Serial</th>
                    <th>Delivered To</th>
                    <th>Station</th>
                    <th>Delivered By</th>
                    <th>Received?</th>
                    <th>Received At</th>
                    <th>Batch Code</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveries as $delivery)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $delivery->battery->serial_number ?? 'N/A' }}</td>
                        <td>{{ $delivery->agent->name ?? 'N/A' }}</td>
                        <td>{{ $delivery->station->name ?? 'N/A' }}</td>
                        <td>{{ $delivery->delivered_by ?? '-' }}</td>
                        <td>
                            @if($delivery->received)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-warning text-dark">No</span>
                            @endif
                        </td>
                        <td>{{ $delivery->received_at ? $delivery->received_at->format('d-M-Y H:i') : '-' }}</td>
                        <td>{{ $delivery->delivery_code }}</td>
                        <td>{{ $delivery->created_at->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
