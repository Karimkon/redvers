@extends('agent.layouts.app')

@section('title', 'Rider Promotions')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 🔖 Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-primary mb-2 mb-md-0">
        <i class="bi bi-stars me-2"></i> Rider Promotions
    </h4>
    
    <a href="{{ route('agent.promotions.create') }}" 
       class="btn btn-success d-flex align-items-center gap-1 px-3 py-2"
       style="font-size: 0.95rem; border-radius: 6px;">
        <i class="bi bi-plus-circle"></i> Assign Promotion
    </a>
</div>



    {{-- ✅ Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 📋 Promotion Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Rider</th>
                            <th>Starts At</th>
                            <th>Ends At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promo)
                            <tr class="text-center">
                                <td class="text-start">{{ $promo->rider->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($promo->starts_at)->format('d M Y, H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($promo->ends_at)->format('d M Y, H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $promo->status === 'active' ? 'success' : 
                                        ($promo->status === 'expired' ? 'secondary' : 'info text-dark')
                                    }}">
                                        {{ ucfirst($promo->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('agent.promotions.edit', $promo) }}" class="btn btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('agent.promotions.destroy', $promo) }}" onsubmit="return confirm('Delete this promotion?')" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle"></i> No promotions assigned yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 📄 Pagination --}}
    <div class="mt-4">
        {{ $promotions->links() }}
    </div>

</div>
@endsection
