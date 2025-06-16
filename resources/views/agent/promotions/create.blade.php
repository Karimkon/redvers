@extends('agent.layouts.app')

@section('title', 'Assign Promotion')

@section('content')
<h3>Assign Unlimited Swap Promotion</h3>

<form action="{{ route('agent.promotions.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="rider_id" class="form-label">Select Rider</label>
        <select name="rider_id" id="rider_id" class="form-select" required>
            <option value="">-- Choose Rider --</option>
            @foreach($riders as $rider)
                <option value="{{ $rider->id }}">{{ $rider->name }} ({{ $rider->phone }})</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-success">Proceed to Pay</button>
</form>
@endsection
