@extends('admin.layouts.app')

@section('title', 'Assign Motorcycle')

@section('content')
<div class="container">
    <h4 class="mb-4">Assign Motorcycle</h4>

    <form action="{{ route('admin.purchases.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Rider</label>
            <select name="user_id" class="form-control select2" required>
                <option value="">-- Select Rider --</option>
                @foreach($riders as $rider)
                    <option value="{{ $rider->id }}">{{ $rider->name }} ({{ $rider->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        

        {{-- Motorcycle Plan --}}
<div class="mb-3">
    <label class="form-label">Motorcycle Plan</label>
    <select name="motorcycle_id" class="form-control" required>
        <option value="">-- Select Plan --</option>
        @foreach($motorcycles as $bike)
            <option value="{{ $bike->id }}">
                {{ ucfirst($bike->type) }} - UGX {{ number_format($bike->cash_price) }} (Cash), UGX {{ number_format($bike->weekly_payment) }}/wk (Hire)
            </option>
        @endforeach
        </select>
    </div>

    {{-- Motorcycle Unit --}}
    <div class="mb-3">
        <label class="form-label">Motorcycle Number Plate</label>
        <select name="motorcycle_unit_id" class="form-control" required>
            <option value="">-- Select Unit --</option>
            @foreach($availableUnits as $unit)
                <option value="{{ $unit->id }}">
                    {{ $unit->number_plate }} ({{ ucfirst($unit->motorcycle->type) }})
                </option>
            @endforeach
        </select>
    </div>


        <div class="mb-3">
            <label class="form-label">Purchase Type</label>
            <select name="purchase_type" class="form-control" required>
                <option value="cash">Cash</option>
                <option value="hire">Hire Purchase</option>
            </select>
        </div>

        <button class="btn btn-success" type="submit">Assign Motorcycle</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(() => {
        $('.select2').select2();
    });
</script>
@endpush
