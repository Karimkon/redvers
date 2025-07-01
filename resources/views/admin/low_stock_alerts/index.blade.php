@extends('admin.layouts.app')

@section('title', 'Low Stock Alerts')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- ✅ Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-danger mb-0">
            <i class="bi bi-exclamation-triangle me-2"></i> Low Stock Alerts
        </h4>
    </div>

    {{-- ✅ Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ⚠️ Table --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Part</th>
                        <th>Shop</th>
                        <th>Remaining Qty</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alerts as $index => $alert)
                        <tr>
                            <td>{{ $alerts->firstItem() + $index }}</td>
                            <td>{{ $alert->part->name }}</td>
                            <td>{{ $alert->shop->name }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $alert->remaining_quantity }} left
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-danger">Critical Low</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.low_stock_alerts.resolve', $alert->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Mark this alert as resolved?')">
                                        <i class="bi bi-check-circle-fill me-1"></i> Resolve
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center py-4">
                                <i class="bi bi-box"></i> No low stock alerts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

     {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        {{ $alerts->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </ul>
                </nav>
            </div>

</div>
@endsection
