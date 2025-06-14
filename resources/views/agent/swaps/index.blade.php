<!-- resources/views/agent/swaps/index.blade.php -->
@extends('agent.layouts.app')

@section('title', 'Swaps')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">My Swaps</h4>
        <a href="{{ route('agent.swaps.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> New Swap
        </a>
    </div>

    @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif


    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Rider</th>
                    <th>Station</th>
                    <th>Battery %</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($swaps as $swap)
                    <tr>
                        <td>{{ $swap->swapped_at->format('Y-m-d') }}</td>
                        <td>{{ $swap->riderUser->name ?? 'N/A' }}</td>
                        <td>{{ $swap->station->name ?? 'N/A' }}</td>
                        <td>{{ $swap->percentage_difference }}%</td>
                        <td>
                            {{ strtoupper($swap->payment_method ?? '-') }}
                            @if($swap->payment && $swap->payment->status === 'completed')
                                <span class="badge bg-success ms-1">Paid</span>
                            @elseif($swap->payment && $swap->payment->status === 'pending')
                                <span class="badge bg-warning ms-1">Pending</span>
                            @endif
                        </td>

                        <td>UGX {{ number_format($swap->payable_amount) }}</td>
                        <td>
                            <a href="{{ route('agent.swaps.show', $swap->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <form action="{{ route('agent.swaps.destroy', $swap->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No swaps found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $swaps->links() }}
    </div>
@endsection
