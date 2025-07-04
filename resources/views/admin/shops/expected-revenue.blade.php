@extends('admin.layouts.app')

@section('title', 'Expected Revenue - ' . $shop->name)

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Expected Revenue for {{ $shop->name }}</h4>
        <a href="{{ route('admin.shops.analytics', $shop) }}" class="btn btn-outline-dark">
            ← Back to Analytics
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Part</th>
                    <th>Stock Qty</th>
                    <th>Selling Price</th>
                    <th>Expected Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parts as $i => $part)
                <tr>
                    <td>{{ $parts->firstItem() + $i }}</td>
                    <td>{{ $part->name }}</td>
                    <td>{{ $part->stock }}</td>
                    <td>UGX {{ number_format($part->price) }}</td>
                    <td class="fw-bold text-primary">
                        UGX {{ number_format($part->stock * $part->price) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 text-end">
        <h5 class="fw-bold">Total Expected Revenue: UGX {{ number_format($totalExpected) }}</h5>
    </div>

     {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-center">
        <nav>
            <ul class="pagination pagination-sm">
                {{ $parts->onEachSide(1)->links('pagination::bootstrap-5') }}
            </ul>
        </nav>
    </div>
</div>
@endsection
