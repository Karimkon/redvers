@extends('agent.layouts.app')

@section('title', 'Rider Promotions')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Promotions</h3>
    <a href="{{ route('agent.promotions.create') }}" class="btn btn-primary">+ Assign Promotion</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Rider</th>
            <th>Starts At</th>
            <th>Ends At</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($promotions as $promo)
        <tr>
            <td>{{ $promo->rider->name }}</td>
            <td>{{ $promo->starts_at }}</td>
            <td>{{ $promo->ends_at }}</td>
            <td><span class="badge bg-info text-dark">{{ ucfirst($promo->status) }}</span></td>
            <td>
                <a href="{{ route('agent.promotions.edit', $promo) }}" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="{{ route('agent.promotions.destroy', $promo) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete this promotion?')" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $promotions->links() }}
@endsection
