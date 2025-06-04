@extends('admin.layouts.app')

@section('title', 'Apply Discount')

@section('content')
<div class="container">
    <h4 class="mb-4">Apply Discount to Purchase #{{ $purchase->id }}</h4>

    <div class="card p-4 mb-4">
        <p><strong>Rider:</strong> {{ $purchase->user->name }} ({{ $purchase->user->email }})</p>
        <p><strong>Total:</strong> UGX {{ number_format($purchase->total_price) }}</p>
        <p><strong>Remaining Balance:</strong> UGX {{ number_format($purchase->remaining_balance) }}</p>
    </div>

    <form action="{{ route('admin.discounts.store', $purchase) }}" method="POST" class="bg-light p-4 rounded">
        @csrf

        <div class="row">
            <div class="col-md-4">
                <label for="amount">Fixed Amount (UGX)</label>
                <input type="number" name="amount" class="form-control" placeholder="e.g. 50000">
            </div>

            <div class="col-md-4">
                <label for="percentage">Or Percentage (%)</label>
                <input type="number" name="percentage" class="form-control" placeholder="e.g. 10">
            </div>

            <div class="col-md-4">
                <label for="reason">Reason</label>
                <input type="text" name="reason" class="form-control" placeholder="Optional reason">
            </div>
        </div>

        <button class="btn btn-success mt-3">Apply Discount</button>
    </form>
</div>
@endsection
