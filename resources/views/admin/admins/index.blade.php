@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-primary">Admin Users</h4>
                    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-1"></i> Add Admin
                    </a>
                </div>

                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search admins...">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </form>

                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <!-- <th>Photo</th> -->
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                        <tr>
                            <!-- <td>
                                <img src="{{ $admin->profile_photo ? asset($admin->profile_photo) : asset('images/default-user.png') }}" alt="Profile" class="rounded-circle" width="50">
                            </td> -->
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->phone }}</td>
                            <td>{{ $admin->email ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this admin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No admins found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
