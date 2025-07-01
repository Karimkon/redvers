@extends('admin.layouts.app')

@section('title', 'Riders')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">
    {{-- Search Form --}}
    <form id="riderSearchForm" method="GET" class="d-flex align-items-center flex-grow-1">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               id="riderSearchInput"
               class="form-control form-control-sm me-2"
               placeholder="Search name, phone or email…"
               autocomplete="off">

        @if(request()->has('q') && request('q') !== '')
            <a href="{{ route('admin.riders.index') }}" class="btn btn-sm btn-outline-secondary">
                Clear
            </a>
        @endif
    </form>

    {{-- Add Rider Button --}}
    <a href="{{ route('admin.riders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Rider
    </a>
</div>



@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riders as $rider)
                        <tr>
                            <td>{{ $rider->id }}</td>
                            <td>{{ $rider->name }}</td>
                            <td>{{ $rider->phone }}</td>
                            <td>{{ $rider->email ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.riders.show', $rider) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.riders.edit', $rider) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.riders.destroy', $rider) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this rider?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No riders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">
    <nav>
        <ul class="pagination pagination-sm">
            {{ $riders->onEachSide(1)->links('pagination::bootstrap-5') }}
        </ul>
    </nav>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('riderSearchInput');
    let debounce;
    input.addEventListener('input', () => {
        clearTimeout(debounce);
        debounce = setTimeout(() => {
            document.getElementById('riderSearchForm').submit();
        }, 500);   // adjust delay to taste
    });
});
</script>
@endpush
