@extends('admin.layouts.app')

@section('title', 'All Motorcycle Purchases')

@section('content')
<div class="container">
    <h4 class="mb-4">Motorcycle Purchases</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary mb-3">Assign New Purchase</a>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <form method="GET" action="{{ route('admin.purchases.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email or phone..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary">Search</button>
        </div>
    </div>
</form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Started On</th>
                    <th>Rider</th>
                    <th>Motorcycle</th>
                    <th>Number Plate</th>
                    <th>Type</th>
                    <th>Deposit</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($purchase->start_date)->format('Y-m-d') }}</td>
                        <td>{{ $purchase->user->name }}</td>
                        <td>{{ ucfirst($purchase->motorcycle->type) }}</td>
                        <td>{{ $purchase->motorcycleUnit->number_plate ?? 'N/A' }}</td>
                        <td>{{ ucfirst($purchase->purchase_type) }}</td>
                        <td>{{ number_format($purchase->initial_deposit) }}</td>
                        <td>{{ number_format($purchase->total_price) }}</td>
                        <td>{{ number_format($purchase->amount_paid) }}</td>
                        <td>{{ number_format($purchase->remaining_balance) }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($purchase->status) }}</span></td>
                        <td>
                            <a href="{{ route('admin.purchases.show', $purchase->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.purchases.edit', $purchase->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
