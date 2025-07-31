@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Mechanics</h4>
    <a href="{{ route('admin.mechanics.create') }}" class="btn btn-success">
        <i class="bi bi-person-plus"></i> Add Mechanic
    </a>
</div>

<form method="GET" class="mb-3">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name, phone or email">
</form>

@if($mechanics->count())
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($mechanics as $mechanic)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($mechanic->profile_photo)
                            <img src="{{ asset($mechanic->profile_photo) }}" alt="Photo" class="rounded-circle" width="40" height="40">
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $mechanic->name }}</td>
                    <td>{{ $mechanic->phone }}</td>
                    <td>{{ $mechanic->email ?? '—' }}</td>
                    <td>{{ $mechanic->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.mechanics.show', $mechanic) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $mechanics->links() }}
@else
    <div class="alert alert-warning">No mechanics found.</div>
@endif
@endsection
