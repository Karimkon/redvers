@extends('admin.layouts.app')

@section('title', 'Assign Promotion')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <h4 class="fw-bold text-primary mb-4">
        <i class="bi bi-stars me-2"></i> Assign Unlimited Swap Promotion
    </h4>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.promotions.store') }}" method="POST" class="card p-4 shadow-sm border-0">
        @csrf

        {{-- Rider Selection --}}
        <div class="mb-3">
            <label for="rider_id" class="form-label fw-semibold">Select Rider</label>
            <select name="rider_id" id="riderSelect" class="form-select select2" required>
                <option value="">-- Choose Rider --</option>
                @foreach($riders as $rider)
                    <option value="{{ $rider->id }}">{{ $rider->name }} ({{ $rider->phone }})</option>
                @endforeach
            </select>
        </div>

        {{-- Agent Selection --}}
        <div class="mb-3">
            <label for="agent_id" class="form-label fw-semibold">Assign Agent</label>
            <select name="agent_id" id="agentSelect" class="form-select select2" required>
                <option value="">-- Choose Agent --</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->email }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-cash-stack me-1"></i> Proceed to Pay
        </button>
    </form>
</div>
@endsection

@push('scripts')
{{-- Select2 Script --}}
<script>
    $(document).ready(function () {
        $('#riderSelect, #agentSelect').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Search rider by name or phone',
            allowClear: true
        });
    });
</script>
@endpush
