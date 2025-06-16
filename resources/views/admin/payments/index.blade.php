@extends('admin.layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container-fluid px-3 px-md-4">
    {{-- Heading --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-cash-coin me-2"></i> Payment Records
        </h3>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🔍 Search --}}
    <div class="mb-4">
        <input type="text" id="paymentSearch" class="form-control shadow-sm" placeholder="Search by rider, method, reference, amount...">
    </div>

    {{-- 💳 Payment Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="paymentsTable">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Rider</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Reference</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $payment->swap->rider->name ?? 'Unknown' }}</td>
                                <td><span class="fw-semibold">{{ number_format($payment->amount) }} UGX</span></td>
                                <td><span class="badge bg-secondary">{{ ucfirst($payment->method) }}</span></td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $payment->status == 'completed' ? 'success' : 
                                        ($payment->status == 'pending' ? 'warning text-dark' : 'danger') 
                                    }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td><code>{{ $payment->reference }}</code></td>
                                <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle"></i> No payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('paymentSearch');
        const rows = document.querySelectorAll('#paymentsTable tbody tr');

        searchInput.addEventListener('input', function () {
            const search = this.value.toLowerCase();
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        });
    });
</script>
@endpush
