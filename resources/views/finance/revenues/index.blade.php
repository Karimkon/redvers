@extends('finance.layouts.app')

@section('title', 'Revenue Records')

@section('content')
<div class="container-fluid py-3">
    <h4 class="mb-4 fw-bold text-primary">💵 All Revenue Entries</h4>

    <div class="mb-3 text-end">
        <a href="{{ route('finance.revenues.create') }}" class="btn btn-success btn-sm">➕ Add Revenue</a>
    </div>

    @if($revenues->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Source</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenues as $revenue)
                        <tr>
                            <td>{{ $revenue->source }}</td>
                            <td>{{ number_format($revenue->amount) }}</td>
                            <td>{{ \Carbon\Carbon::parse($revenue->date)->format('d M Y') }}</td>
                            <td>{{ ucfirst($revenue->payment_method) }}</td>
                            <td>
                                <a href="{{ route('finance.revenues.show', $revenue) }}" class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('finance.revenues.edit', $revenue) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('finance.revenues.destroy', $revenue) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this revenue record?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $revenues->links() }}
    @else
        <div class="alert alert-info">No revenue entries found.</div>
    @endif
</div>
@endsection
