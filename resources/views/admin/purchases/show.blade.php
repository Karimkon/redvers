@extends('admin.layouts.app')

@section('title', 'Purchase Details')

@section('content')
<div class="container">

    {{-- 🔙 Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Purchase Details</h4>
        <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Purchases
        </a>
    </div>

    {{-- 👤 Rider Info --}}
    <div class="card mb-4 shadow-sm border-start border-primary border-3">
        <div class="card-body d-flex justify-content-between flex-wrap align-items-start">
            <div class="me-4">
                <h5 class="card-title mb-2">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ $purchase->user->name }}
                    <small class="text-muted">({{ $purchase->user->email }})</small>
                </h5>

                <p class="mb-1"><strong><i class="bi bi-calendar-event me-1"></i>Started On:</strong> {{ $purchase->start_date ? \Carbon\Carbon::parse($purchase->start_date)->translatedFormat('F jS, Y') : 'Not Set' }}</p>
                <p class="mb-1"><strong>Motorcycle:</strong> {{ ucfirst($purchase->motorcycle->type) }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $purchase->user->phone ?? 'N/A' }}</p>
                <p class="mb-1"><strong>NIN Number:</strong> {{ $purchase->user->nin_number ?? 'Not Provided' }}</p>
                <p class="mb-1"><strong>Purchase Type:</strong> {{ ucfirst($purchase->purchase_type) }}</p>
                <p class="mb-1"><strong>Hire Plan Total:</strong> UGX {{ number_format($purchase->motorcycle->hire_price_total) }}</p>
                <p class="mb-1"><strong>Initial Deposit:</strong> UGX {{ number_format($purchase->initial_deposit) }}</p>
                <p class="mb-1"><strong>Amount Paid (Including Discounts):</strong> UGX {{ number_format($trueAmountPaid) }}</p>
                <small class="text-muted">
                    (Payments: UGX {{ number_format($purchase->payments->sum('amount')) }},
                    Discounts: UGX {{ number_format($totalDiscount) }})
                </small>

                <p class="mt-2 mb-1"><strong>Remaining Balance:</strong> UGX {{ number_format($purchase->remaining_balance) }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $purchase->status === 'active' ? 'info' : ($purchase->status === 'cleared' ? 'success' : 'danger') }}">
                        {{ ucfirst($purchase->status) }}
                    </span>
                </p>
            </div>

            @if($purchase->user->profile_photo)
            <div class="text-end">
                <img src="{{ asset('storage/' . $purchase->user->profile_photo) }}"
                     alt="Profile Photo"
                     class="rounded-circle shadow border border-primary"
                     style="width: 120px; height: 120px; object-fit: cover;">
            </div>
            @endif
        </div>
    </div>

    {{-- 💸 Add Payment --}}
    <div class="card mb-4 border-start border-info border-3">
        <div class="card-header">
            <i class="bi bi-cash-coin me-2"></i> Add Payment
        </div>
        <div class="card-body">
            <form action="{{ route('admin.motorcycle-payments.store', $purchase->id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="lump_sum">Lump Sum</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Optional">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 📜 Payment History --}}
    @if($purchase->payments->count())
    <div class="card mb-4 border-start border-primary border-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <i class="bi bi-list-ul me-2"></i> Payment History
            <input type="text" class="form-control form-control-sm w-25" placeholder="Search..." onkeyup="filterPayments(this)">
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-sm mb-0" id="paymentTable">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date }}</td>
                            <td>UGX {{ number_format($payment->amount) }}</td>
                            <td>{{ ucfirst($payment->type) }}</td>
                            <td>{{ $payment->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <th colspan="3" class="text-end">Total Paid (Including Discounts):</th>
                        <th>UGX {{ number_format($purchase->payments->sum('amount') + $purchase->discounts->sum('amount')) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    {{-- 📅 Payment Schedule --}}
    <div class="card mb-4 border-start border-info border-3">
        <div class="card-header">
            <i class="bi bi-calendar-check me-2"></i> Payment Schedule Summary
        </div>
        <div class="card-body">
            <p><strong>Expected Payments:</strong> {{ $schedule['expected_days'] }}</p>
            <p><strong>Payments Made:</strong> {{ $schedule['actual_payments'] }}</p>
            <p>
                <strong>Missed Payments:</strong>
                <span class="badge bg-{{ $schedule['missed_payments'] > 0 ? 'danger' : 'success' }}">
                    {{ $schedule['missed_payments'] }}
                </span>
            </p>
            @if(count($schedule['missed_dates']))
            <details class="border rounded p-2 mb-2">
                <summary class="text-danger">Missed Dates</summary>
                <ul class="small text-danger mb-0">
                    @foreach($schedule['missed_dates'] as $day)
                        <li>{{ $day }}</li>
                    @endforeach
                </ul>
            </details>
            @endif

            <p>
                <strong>Next Due Date:</strong>
                <span class="badge bg-warning text-dark">
                    {{ $schedule['next_due_date'] ?? 'N/A' }}
                </span>
            </p>

            @if(isset($schedule['remaining_expected_amount']))
            <p><strong>Remaining Expected Amount:</strong> UGX {{ number_format($schedule['remaining_expected_amount']) }}</p>
            @endif

            @if(isset($schedule['paid_ahead_days']) && $schedule['paid_ahead_days'] > 0)
            <p>
                <strong>Advance Payments:</strong>
                <span class="badge bg-success">{{ $schedule['paid_ahead_days'] }} day{{ $schedule['paid_ahead_days'] > 1 ? 's' : '' }} ahead</span>
                <br>
                @if(isset($schedule['overpaid_amount']))
                <small class="text-muted">Overpaid by UGX {{ number_format($schedule['overpaid_amount']) }}</small>
                @endif
            </p>
            @endif
        </div>
    </div>

    {{-- 🎁 Discount History --}}
    @if($purchase->discounts->count())
    <div class="card mb-4 border-start border-success border-3">
        <div class="card-header">
            <i class="bi bi-tags me-2"></i> Discount History
        </div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Percentage</th>
                        <th>Reason</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->discounts as $discount)
                        <tr>
                            <td>{{ $discount->amount ? 'UGX ' . number_format($discount->amount) : '-' }}</td>
                            <td>{{ $discount->percentage ? $discount->percentage . '%' : '-' }}</td>
                            <td>{{ $discount->reason ?? '-' }}</td>
                            <td>{{ $discount->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ➕ Apply Discount --}}
    <a href="{{ route('admin.discounts.create', $purchase) }}" class="btn btn-outline-primary mt-3">
        <i class="bi bi-percent me-1"></i> Apply Discount
    </a>
</div>

{{-- 🔍 Filter script --}}
<script>
    function filterPayments(input) {
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll("#paymentTable tbody tr");
        rows.forEach(row => {
            const match = [...row.cells].some(cell =>
                cell.textContent.toLowerCase().includes(filter)
            );
            row.style.display = match ? "" : "none";
        });
    }
</script>
@endsection
