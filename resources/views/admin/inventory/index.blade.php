@extends('admin.layouts.app')

@section('title', 'Inventory Operators')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">Inventory Operators</h4>
        <a href="{{ route('admin.inventory.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Add Operator
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Shop</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($operators as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email ?? '—' }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @if($user->shop)
                                <span class="badge bg-success">{{ $user->shop->name }}</span>
                            @else
                                <span class="badge bg-secondary">Unassigned</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.inventory.show', $user) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.inventory.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.inventory.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this operator?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $operators->links() }}
        </div>
    </div>
</div>
@endsection
