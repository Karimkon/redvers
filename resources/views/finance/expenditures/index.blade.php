@extends('finance.layouts.app')

@section('title', 'Expenditures')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">💸 Expenditures</h4>

    <a href="{{ route('finance.expenditures.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Add Expenditure
    </a>

    <div class="card shadow-sm p-3 table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenditures as $i => $expenditure)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $expenditure->category }}</td>
                    <td>UGX {{ number_format($expenditure->amount) }}</td>
                    <td>{{ ucfirst($expenditure->payment_method) }}</td>
                    <td>{{ \Carbon\Carbon::parse($expenditure->date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('finance.expenditures.show', $expenditure) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('finance.expenditures.edit', $expenditure) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('finance.expenditures.destroy', $expenditure) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this expenditure?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $expenditures->links() }}
        </div>
    </div>
</div>
@endsection
