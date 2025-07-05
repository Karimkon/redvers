@extends('admin.layouts.app')

@section('title', 'Finance Staff')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">
    {{-- Search --}}
    <form id="financeSearchForm" method="GET" class="d-flex align-items-center flex-grow-1">
        <input name="q" id="financeSearchInput"
               value="{{ request('q') }}"
               class="form-control form-control-sm me-2"
               placeholder="Search name, phone or email…"
               autocomplete="off">
        @if(request('q'))
            <a href="{{ route('admin.finance.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        @endif
    </form>

    {{-- Add --}}
    <a href="{{ route('admin.finance.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Staff
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm mt-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($finances as $staff)
                        <tr>
                            <td>{{ $staff->id }}</td>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->phone }}</td>
                            <td>{{ $staff->email ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.finance.show', $staff) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.edit', $staff) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.finance.destroy', $staff) }}" method="POST"
                                          onsubmit="return confirm('Delete this staff member?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No staff found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">
    {{ $finances->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const input=document.getElementById('financeSearchInput');
    let d;input.addEventListener('input',()=>{clearTimeout(d);d=setTimeout(()=>{document.getElementById('financeSearchForm').submit();},500);});
});
</script>
@endpush
