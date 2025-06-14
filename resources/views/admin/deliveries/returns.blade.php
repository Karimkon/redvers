@extends('admin.layouts.app')

@section('title', 'Returned Batteries Management')

@section('content')
<div class="container">
    <h3 class="mb-4">Manage Returned Batteries</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($deliveries->isEmpty())
        <div class="alert alert-info">🎉 No batteries pending return. All returned batteries have been processed.</div>
    @else
        <form method="POST" action="{{ route('admin.deliveries.acceptReturns') }}" id="returnForm">
            @csrf
            <div class="mb-3">
            <input type="text" class="form-control" id="batterySearchInput" placeholder="Search by battery, station, agent, delivered by...">
        </div>

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                        <th>Battery</th>
                        <th>Station</th>
                        <th>Delivered By</th>
                        <th>Agent</th>
                        <th>Received At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveries as $delivery)
                        <tr>
                            <td><input type="checkbox" name="delivery_ids[]" value="{{ $delivery->id }}" onchange="toggleSubmit()"></td>
                            <td>{{ $delivery->battery->serial_number }}</td>
                            <td>{{ $delivery->station->name }}</td>
                            <td>{{ $delivery->delivered_by }}</td>
                            <td>{{ $delivery->agent->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($delivery->received_at)->format('d-M-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary mt-3" id="submitButton" disabled>Accept Selected Returns</button>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('input[name="delivery_ids[]"]');
        checkboxes.forEach(cb => cb.checked = master.checked);
        toggleSubmit();
    }

    function toggleSubmit() {
        const checkboxes = document.querySelectorAll('input[name="delivery_ids[]"]:checked');
        document.getElementById('submitButton').disabled = checkboxes.length === 0;
    }

    document.getElementById('batterySearchInput').addEventListener('input', function () {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>
@endpush
