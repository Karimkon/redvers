@extends('admin.layouts.app')

@section('title', 'Dispatch Batteries')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            <i class="bi bi-truck-front-fill me-2 text-primary"></i> Dispatch Batteries to Agent
        </h3>
    </div>

    <form method="POST" action="{{ route('admin.deliveries.store') }}" class="card shadow-sm p-4">
        @csrf

        {{-- Agent --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">
                <i class="bi bi-person-badge-fill me-1 text-secondary"></i> Delivering Agent
            </label>
            <select class="form-select" name="agent_id" required>
                <option value="">Select Agent</option>
                @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}">
                        {{ $agent->name }} — {{ $agent->station->name ?? 'No Station' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Batteries --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">
                <i class="bi bi-battery me-1 text-secondary"></i> Batteries to Dispatch
            </label>
            <select class="form-select select2" name="battery_ids[]" multiple="multiple" required>
                @foreach ($batteries as $battery)
                    <option value="{{ $battery->id }}">
                        {{ $battery->serial_number }} — {{ ucfirst($battery->status) }}
                        @if ($battery->currentStation)
                            ({{ $battery->currentStation->name }})
                        @endif
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple batteries.</small>
        </div>

        {{-- Delivered By --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">
                <i class="bi bi-truck me-1 text-secondary"></i> Delivered By (Driver or Truck)
            </label>
            <input type="text" name="delivered_by" class="form-control" placeholder="e.g. John Doe / Truck #7">
        </div>

        <button type="submit" class="btn btn-primary w-100 shadow">
            <i class="bi bi-send-check me-1"></i> Dispatch Batteries
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select batteries to dispatch',
            width: '100%',
            dropdownParent: $('.card') // fixes modal overlap if ever used inside modal
        });
    });
</script>
@endpush
