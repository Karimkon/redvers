@extends('finance.layouts.app')

@section('title', 'Loan Records')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>💳 Loan Records</h4>
        <a href="{{ route('finance.loans.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Loan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Lender</th>
                        <th>Amount</th>
                        <th>Interest Rate</th>
                        <th>Status</th>
                        <th>Issued</th>
                        <th>Due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->id }}</td>
                            <td>{{ $loan->lender }}</td>
                            <td>UGX {{ number_format($loan->amount) }}</td>
                            <td>{{ $loan->interest_rate }}%</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($loan->status) }}</span></td>
                            <td>{{ $loan->issued_date }}</td>
                            <td>{{ $loan->due_date }}</td>
                            <td>
                                <a href="{{ route('finance.loans.show', $loan) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('finance.loans.edit', $loan) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('finance.loans.destroy', $loan) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this loan?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No loans found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
