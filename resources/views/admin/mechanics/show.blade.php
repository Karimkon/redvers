@extends('admin.layouts.app')

@section('title', 'Mechanic Profile - ' . $mechanic->name)

@section('content')
<div class="container">
    <div class="card shadow-sm p-4">
        <div class="row g-4">
            <div class="col-md-3 text-center">
                @if($mechanic->profile_photo)
                    <img src="{{ asset($mechanic->profile_photo) }}" class="img-fluid rounded-circle shadow" alt="Profile Picture">
                @else
                    <i class="bi bi-person-circle display-4 text-muted"></i>
                @endif
            </div>

            <div class="col-md-9">
                <h4 class="fw-bold">{{ $mechanic->name }}</h4>
                <p><strong>Phone:</strong> {{ $mechanic->phone }}</p>
                <p><strong>Email:</strong> {{ $mechanic->email ?? '—' }}</p>
                <p><strong>Role:</strong> Mechanic</p>

                <div class="mt-3">
                    <a href="{{ route('admin.mechanics.edit', $mechanic->id) }}" class="btn btn-warning me-2"><i class="bi bi-pencil-square"></i> Edit</a>
                    <form method="POST" action="{{ route('admin.mechanics.destroy', $mechanic->id) }}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
                    </form>
                    <a href="{{ route('admin.mechanics.index') }}" class="btn btn-secondary float-end">← Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
