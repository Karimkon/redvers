@extends('admin.layouts.app')

@section('title', 'Motorcycle Units')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0 d-flex align-items-center">
            <img src="{{ asset('images/motorcycle-icon.png') }}" alt="Motorcycle" width="28" class="me-2"> Redvers Motorcycle Units
        </h3>
        <a href="{{ route('admin.motorcycle-units.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Unit
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search Bar --}}
    <div class="mb-3">
        <input type="text" class="form-control shadow-sm" id="unitSearchInput" placeholder="🔍 Search by number plate, type, status, rider...">
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-hover align-middle mb-0" id="unitTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Plate</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $unit->number_plate }}</td>
                        <td>
                            <span class="badge bg-info text-dark text-uppercase">
                                {{ ucfirst($unit->motorcycle->type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $unit->status === 'available' ? 'success' : ($unit->status === 'assigned' ? 'warning' : 'danger') }}">
                                {{ ucfirst($unit->status) }}
                            </span>
                        </td>
                        <td>
                            @if($unit->assigned_purchase)
                                <div>
                                    <strong>{{ $unit->assigned_purchase->user->name }}</strong><br>
                                    <small class="text-muted">{{ $unit->assigned_purchase->user->phone }}</small>
                                </div>
                            @else
                                <span class="text-muted fst-italic">Unassigned</span>
                            @endif
                        </td>
                        <td>{{ $unit->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.motorcycle-units.edit', $unit->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.motorcycle-units.destroy', $unit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($units->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center text-muted">No motorcycle units found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- Instant Filter Script --}}
@push('scripts')
<script>
    document.getElementById("unitSearchInput").addEventListener("input", function () {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll("#unitTable tbody tr");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    });
</script>
@endpush
@endsection
