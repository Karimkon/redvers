@extends('admin.layouts.app')

@section('title', 'Dispatch Batteries')

@section('content')
<div class="container">
    <h3 class="mb-4">Dispatch Batteries to Agent</h3>

    <form method="POST" action="{{ route('admin.deliveries.store') }}">
        @csrf

        <div class="mb-3">
            <label>Delivering Agent</label>
            <select class="form-select" name="agent_id" required>
                <option value="">Select Agent</option>
                @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}">
                        {{ $agent->name }} — {{ $agent->station->name ?? 'No Station' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Batteries (hold Ctrl/Shift to select multiple)</label>
            <select class="form-select select2" name="battery_ids[]" multiple="multiple" required>
                @foreach ($batteries as $battery)
                    <option value="{{ $battery->id }}">
                        {{ $battery->serial_number }} — {{ $battery->status }}
                        @if ($battery->currentStation)
                            ({{ $battery->currentStation->name }})
                        @endif
                    </option>
                @endforeach
            </select>

            </select>
        </div>

        <div class="mb-3">
            <label>Delivered By (Truck/Driver Name)</label>
            <input type="text" name="delivered_by" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Dispatch</button>
    </form>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select batteries to dispatch',
            width: '100%',
        });
    });
</script>
@endpush

@endsection
