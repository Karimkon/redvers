@extends('finance.layouts.app')

@section('title', 'All Investors')

@section('content')
<div class="container-fluid py-3">
    <h4 class="mb-4 fw-bold text-primary">📊 Investor Contributions</h4>

    <div class="mb-3 text-end">
        <a href="{{ route('finance.investors.create') }}" class="btn btn-success btn-sm">
            ➕ Add Investor
        </a>
    </div>

    @if($investors->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Contribution (UGX)</th>
                        <th>Ownership (%)</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($investors as $investor)
                        <tr>
                            <td>{{ $investor->name }}</td>
                            <td>{{ number_format($investor->contribution) }}</td>
                            <td>{{ $investor->ownership_percentage ?? '-' }}</td>
                            <td>{{ ucfirst($investor->payment_method) }}</td>
                            <td>{{ \Carbon\Carbon::parse($investor->date)->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('finance.investors.show', $investor) }}" class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('finance.investors.edit', $investor) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('finance.investors.destroy', $investor) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this investor?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $investors->links() }}
    @else
        <div class="alert alert-info">No investors recorded yet.</div>
    @endif
</div>
@endsection
