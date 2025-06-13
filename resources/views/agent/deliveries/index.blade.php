@extends('agent.layouts.app')
@section('title', 'My Battery Deliveries')

@section('content')
<div class="container">
    <h3 class="mb-4">Pending & Past Battery Deliveries</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <form method="POST" action="{{ route('agent.deliveries.acceptMultiple') }}">
        @csrf

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>#</th>
                        <th>Battery Serial</th>
                        <th>Station</th>
                        <th>Received?</th>
                        <th>Received At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveries as $delivery)
                        <tr>
                            <td>
                                @if(!$delivery->received)
                                    <input type="checkbox" name="delivery_ids[]" value="{{ $delivery->id }}">
                                @endif
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $delivery->battery->serial_number ?? 'N/A' }}</td>
                            <td>{{ $delivery->station->name ?? 'N/A' }}</td>
                            <td>
                                @if($delivery->received)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-warning text-dark">No</span>
                                @endif
                            </td>
                            <td>
                                {{ $delivery->received_at ? \Carbon\Carbon::parse($delivery->received_at)->format('d-M-Y H:i') : '-' }}
                            </td>
                            <td>
                                @if($delivery->received)
                                    <span class="text-muted">Received</span>
                                @else
                                    <span class="text-primary">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="bi bi-check-circle"></i> Accept Selected Deliveries
        </button>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="delivery_ids[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
@endsection
