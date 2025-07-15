@extends('admin.layouts.app')

@section('title', 'Swaps')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
    <h2 class="mb-0">Battery Swaps</h2>
    <a href="{{ route('admin.swaps.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i> Swap Rider
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
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Rider</th>
                        <th>Battery Issued</th>
                        <th>Battery Returned</th>
                        <th>Station</th>
                        <th>Agent</th>
                        <th>Battery %</th>
                        <th>Payable</th>
                        <th>Payment</th>
                        <th>Swapped At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($swaps as $swap)
                    <tr>
                        <td>{{ $swap->id }}</td>
                        <td>{{ $swap->riderUser?->name ?? 'N/A' }}</td>
                        <td>{{ $swap->batteryIssued->serial_number ?? 'N/A' }}</td>
                        <td>{{ $swap->returnedBattery->serial_number ?? 'None' }}</td>
                        <td>{{ $swap->station?->name ?? 'N/A' }}</td>
                        <td>{{ $swap->agentUser?->name ?? '—' }}</td>
                        <td>{{ number_format($swap->percentage_difference, 2) }}%</td>
                        <td>{{ number_format($swap->payable_amount, 2) }} UGX</td>
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

                        <td>{{ $swap->swapped_at ? \Carbon\Carbon::parse($swap->swapped_at)->format('d M Y H:i') : '—' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <a href="{{ route('admin.swaps.show', $swap) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.swaps.edit', $swap) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.swaps.destroy', $swap) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">No swaps found.</td>
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
            {{ $swaps->onEachSide(1)->links('pagination::bootstrap-5') }}
        </ul>
    </nav>
</div>
@endsection
