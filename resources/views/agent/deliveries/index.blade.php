@extends('agent.layouts.app')
@section('title', 'My Battery Deliveries')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Page Header --}}
    <h4 class="fw-bold text-primary mb-4">
        <i class="bi bi-truck-front me-2"></i> Pending & Past Battery Deliveries
    </h4>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif (session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form & Table --}}
    <form method="POST" action="{{ route('agent.deliveries.acceptMultiple') }}">
        @csrf

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>#</th>
                                <th>Battery Serial</th>
                                <th>Station</th>
                                <th>Received?</th>
                                <th>Received At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deliveries as $delivery)
                                <tr class="text-center">
                                    <td>
                                        @if (!$delivery->received)
                                            <input type="checkbox" name="delivery_ids[]" value="{{ $delivery->id }}">
                                        @endif
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $delivery->battery->serial_number ?? 'N/A' }}</td>
                                    <td>{{ $delivery->station->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $delivery->received ? 'success' : 'warning text-dark' }}">
                                            {{ $delivery->received ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $delivery->received_at ? \Carbon\Carbon::parse($delivery->received_at)->format('d M Y, H:i') : '—' }}
                                    </td>
                                    <td>
                                        @if ($delivery->received)
                                            <span class="text-muted">Received</span>
                                        @else
                                            <span class="text-primary fw-semibold">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle"></i> No deliveries found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Action Button --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i> Accept Selected Deliveries
            </button>
        </div>
        {{-- 🔄 Show More / Less Toggle --}}
<div class="mt-4 text-center">
    @if (!$showAll)
        <a href="{{ route('agent.deliveries.index', ['show' => 'all']) }}" class="btn btn-outline-primary">
            <i class="bi bi-eye"></i> View All Deliveries
        </a>
    @else
        <a href="{{ route('agent.deliveries.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-chevron-up"></i> Show Less
        </a>
    @endif
</div>

    </form>
</div>

{{-- JS: Select All --}}
@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('input[name="delivery_ids[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
@endsection
