@extends('finance.layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-5">
   <h3 class="fw-bold text-primary mb-0 d-flex align-items-center">
            <img src="{{ asset('images/motorcycle-icon.png') }}" alt="Motorcycle" width="28" class="me-2">Redvers Motorcycle Purchases 
    </h3>
    <br>
    {{-- 🔍 Search --}}
    <form method="GET" class="row gy-2 gx-3 align-items-center mb-4">
        <div class="col-12 col-md-6 col-lg-4">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search by name, phone, status, etc." 
                value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
            <a href="{{ route('finance.purchases.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    {{-- 📊 Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Rider</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Motorcycle</th>
                    <th>Purchase Type</th>
                    <th>Deposit</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>View</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->user->name }}</td>
                        <td>{{ $purchase->user->phone }}</td>
                        <td>{{ $purchase->user->email }}</td>
                        <td>{{ ucfirst($purchase->motorcycle->type) }}</td>
                        <td>{{ ucfirst($purchase->purchase_type) }}</td>
                        <td>UGX {{ number_format($purchase->initial_deposit) }}</td>
                        <td>UGX {{ number_format($purchase->amount_paid) }}</td>
                        <td>UGX {{ number_format($purchase->remaining_balance) }}</td>
                        <td>
                            <a href="{{ route('finance.purchases.show', $purchase->id) }}" class="btn btn-sm btn-info">Details</a>
                        </td>
                        <td>
                            <span class="badge bg-dark">{{ ucfirst($purchase->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No purchases found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
