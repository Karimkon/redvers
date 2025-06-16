@extends('admin.layouts.app')

@section('title', 'Returned Batteries Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            <i class="bi bi-arrow-return-left me-2 text-primary"></i> Returned Batteries Management
        </h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if ($deliveries->isEmpty())
        <div class="alert alert-info shadow-sm">
            <i class="bi bi-stars me-2"></i> 🎉 All batteries have been returned and recorded successfully.
        </div>
    @else
        <form method="POST" action="{{ route('admin.deliveries.acceptReturns') }}" id="returnForm" class="card shadow-sm p-4">
            @csrf

            {{-- Search --}}
            <div class="mb-3">
                <input type="text" class="form-control shadow-sm" id="batterySearchInput" placeholder="🔍 Search battery, station, agent, driver...">
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                            <th><i class="bi bi-battery"></i> Battery</th>
                            <th><i class="bi bi-geo-alt-fill"></i> Station</th>
                            <th><i class="bi bi-truck"></i> Delivered By</th>
                            <th><i class="bi bi-person-badge-fill"></i> Agent</th>
                            <th><i class="bi bi-clock"></i> Received At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveries as $delivery)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="delivery_ids[]" value="{{ $delivery->id }}" onchange="toggleSubmit()">
                                </td>
                                <td>{{ $delivery->battery->serial_number }}</td>
                                <td>{{ $delivery->station->name }}</td>
                                <td>{{ $delivery->delivered_by }}</td>
                                <td>{{ $delivery->agent->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($delivery->received_at)->format('d-M-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-success mt-4 w-100 shadow" id="submitButton" disabled>
                <i class="bi bi-check2-all me-1"></i> Accept Selected Returns
            </button>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function toggleSelectAll(master) {
        document.querySelectorAll('input[name="delivery_ids[]"]').forEach(cb => {
            cb.checked = master.checked;
        });
        toggleSubmit();
    }

    function toggleSubmit() {
        const selected = document.querySelectorAll('input[name="delivery_ids[]"]:checked');
        document.getElementById('submitButton').disabled = selected.length === 0;
    }

    document.getElementById('batterySearchInput').addEventListener('input', function () {
        const value = this.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        });
    });
</script>
@endpush
