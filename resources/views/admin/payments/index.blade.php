@extends('admin.layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container">
    <h2 class="mb-4">All Payments</h2>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Search Field --}}
    <div class="mb-3">
        <input type="text" id="paymentSearch" class="form-control" placeholder="Search by rider, method, reference, amount...">
    </div>

    {{-- 💳 Payments Table --}}
    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <table class="table table-bordered table-hover" id="paymentsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rider</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Reference</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->swap->rider->name ?? 'Unknown' }}</td>
                        <td>{{ number_format($payment->amount) }} UGX</td>
                        <td>{{ ucfirst($payment->method) }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $payment->status == 'completed' ? 'success' : 
                                ($payment->status == 'pending' ? 'warning' : 'danger') 
                            }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->reference }}</td>
                        <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

{{-- 🔍 Auto Search Script --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('paymentSearch');
        const tableRows = document.querySelectorAll('#paymentsTable tbody tr');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();

            tableRows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(query) ? '' : 'none';
            });
        });
    });
</script>
@endpush
