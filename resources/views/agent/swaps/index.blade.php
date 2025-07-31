@extends('agent.layouts.app')

@section('title', 'Swaps')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- 📄 Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-arrow-repeat me-2 text-primary"></i> My Swaps
        </h4>
        <a href="{{ route('agent.swaps.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> New Swap
        </a>
    </div>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 📋 Swaps Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Date</th>
                            <th>Rider</th>
                            <th>Station</th>
                            <th>Battery %</th>
                            <th>Amount</th>
                            <th>Method & Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($swaps as $swap)
                            <tr class="text-center">
                                <td>
                                    {{ $swap->swapped_at ? $swap->swapped_at->format('D, d M Y — h:i:s A') : 'Pending' }}
                                </td>
                                <td class="text-start">{{ $swap->riderUser->name ?? 'N/A' }}</td>
                                <td>{{ $swap->station->name ?? 'N/A' }}</td>
                                <td>{{ $swap->percentage_difference }}%</td>
                                <td>UGX {{ number_format($swap->payable_amount) }}</td>
                                <td>
                                    <div>
                                        <span class="badge bg-secondary">{{ strtoupper($swap->payment_method ?? '-') }}</span>
                                        @if($swap->payment && $swap->payment->status === 'completed')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($swap->payment && $swap->payment->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('agent.swaps.show', $swap->id) }}" class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <!-- <form action="{{ route('agent.swaps.destroy', $swap->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle"></i> No swaps found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

     {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{ $swaps->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </ul>
                </nav>
            </div>

</div>
@endsection
