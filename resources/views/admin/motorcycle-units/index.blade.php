@extends('admin.layouts.app')

@section('title', 'Motorcycle Units')

@section('content')
<div class="container">
    <h4 class="mb-4">All Motorcycle Units</h4>

    <a href="{{ route('admin.motorcycle-units.create') }}" class="btn btn-primary mb-3">Add New Unit</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter Input --}}
    <div class="mb-3">
        <input type="text" class="form-control" id="unitSearchInput" placeholder="Search by number plate, type, status...">
    </div>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <table class="table table-bordered table-hover" id="unitTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Number Plate</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->number_plate }}</td>
                        <td>{{ ucfirst($unit->motorcycle->type) }}</td>
                        <td>
                            <span class="badge bg-{{ $unit->status == 'available' ? 'success' : ($unit->status == 'assigned' ? 'warning' : 'danger') }}">
                                {{ ucfirst($unit->status) }}
                            </span>
                        </td>
                        <td>
                            @if($unit->assigned_purchase)
                                {{ $unit->assigned_purchase->user->name }}<br>
                                <small class="text-muted">{{ $unit->assigned_purchase->user->phone }}</small>
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </td>
                        <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.motorcycle-units.edit', $unit->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.motorcycle-units.destroy', $unit->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Instant Filter Script --}}
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
@endsection
