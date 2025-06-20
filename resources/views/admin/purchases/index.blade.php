@extends('admin.layouts.app')

@section('title', 'All Motorcycle Purchases')

@section('content')
<div class="container-fluid px-3 px-md-4">
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h3 class="fw-bold text-primary mb-0 d-flex align-items-center">
            <img src="{{ asset('images/motorcycle-icon.png') }}" alt="Motorcycle" width="28" class="me-2"> Motorcycle Purchases
        </h3>

        <a href="{{ route('admin.purchases.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Assign New Purchase
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search Filter --}}
    <form method="GET" action="{{ route('admin.purchases.index') }}" class="row gx-2 gy-2 align-items-center mb-4">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control shadow-sm" placeholder="Search by rider name, phone or email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-search me-1"></i> Search
            </button>
        </div>
    </form>

    {{-- Purchases Table --}}
    <div class="table-responsive bg-white p-3 rounded shadow-sm border">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="table-light">
                <tr>
                    <th>Started On</th>
                    <th>Rider</th>
                    <th>Motorcycle</th>
                    <th>Number Plate</th>
                    <th>Type</th>
                    <th>Deposit</th>
                    <th>Total</th>
                    <th>Paid (Inc. Discounts)</th>
                    <th>Remaining Balance</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($purchase->start_date)->format('d M Y') }}</td>
                        <td class="text-start">{{ $purchase->user->name }}</td>
                        <td>{{ ucfirst($purchase->motorcycle->type) }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $purchase->motorcycleUnit->number_plate ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ ucfirst($purchase->purchase_type) }}</td>
                        <td>{{ number_format($purchase->initial_deposit) }}</td>
                        <td>{{ number_format($purchase->total_price) }}</td>
                        @php
                            $initialDeposit = $purchase->initial_deposit ?? 0;
                            $truePaid = $initialDeposit + $purchase->payments->sum('amount') + $purchase->discounts->sum('amount');
                            $trueRemaining = max($purchase->total_price - $truePaid, 0);
                        @endphp


                        <td>{{ number_format($truePaid) }}</td>
                        <td>
                            <span class="text-danger fw-bold">
                                {{ number_format($trueRemaining) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-{{ 
                                $purchase->status == 'active' ? 'success' : 
                                ($purchase->status == 'defaulted' ? 'danger' : 'secondary') 
                            }}">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.purchases.show', $purchase->id) }}" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.purchases.edit', $purchase->id) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle"></i> No motorcycle purchases found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
