<!-- resources/views/inventory/sales/index.blade.php -->
@extends('inventory.layouts.app')

@section('title', 'Sales Records')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- 📊 Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">
                <i class="bi bi-receipt me-2"></i>Sales Records
            </h4>
            <p class="text-muted mb-0">Manage and track all spare part sales</p>
        </div>
        <a href="{{ route('inventory.sales.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> New Sale
        </a>
    </div>

    {{-- 📈 Sales Summary Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Sales</h6>
                            <h4 class="fw-bold text-primary mb-0">
                                UGX {{ number_format($sales->sum('total_price')) }}
                            </h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-currency-dollar text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Items Sold</h6>
                            <h4 class="fw-bold text-success mb-0">
                                {{ $sales->sum('quantity') }}
                            </h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Average Sale</h6>
                            <h4 class="fw-bold text-info mb-0">
                                UGX {{ number_format($sales->avg('total_price') ?? 0) }}
                            </h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-graph-up text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Today's Sales</h6>
                            <h4 class="fw-bold text-warning mb-0">
                                UGX {{ number_format($sales->where('sold_at', '>=', today())->sum('total_price')) }}
                            </h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-calendar-day text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🎯 Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 📋 Sales Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Sales</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportToCSV()">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="salesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Part Name</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total Amount</th>
                            <th>Profit</th>
                            <th>Customer</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-gear-fill text-primary"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $sale->part->name }}</strong>
                                            <small class="text-muted">Stock: {{ $sale->part->stock }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">{{ $sale->quantity }}</span>
                                </td>
                                <td>
                                    <strong>UGX {{ number_format($sale->selling_price) }}</strong>
                                </td>
                                <td>
                                    <strong class="text-success">UGX {{ number_format($sale->total_amount) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $profit = $sale->profit ?? (($sale->selling_price - $sale->cost_price) * $sale->quantity);
                                    @endphp
                                    <span class="badge bg-{{ $profit >= 0 ? 'success' : 'danger' }} rounded-pill">
                                        UGX {{ number_format($profit) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $sale->customer_name ?? 'Walk-in Customer' }}</span>
                                </td>
                                <td>
                                    @if($sale->payment_method === 'pesapal')
                                        <span class="badge bg-success">
                                            <i class="bi bi-credit-card me-1"></i>Online
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            <i class="bi bi-cash me-1"></i>Cash
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($sale->sold_at)->format('d M Y') }}<br>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($sale->sold_at)->format('h:i A') }}</span>
                                    </small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="tooltip" 
                                                title="View Details"
                                                onclick="viewSale({{ $sale->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <form action="{{ route('inventory.sales.destroy', $sale) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this sale? This will restore stock.');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Delete Sale">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-5">
                                        <i class="bi bi-receipt display-1 text-muted"></i>
                                        <h5 class="text-muted mt-3">No Sales Recorded</h5>
                                        <p class="text-muted">Start recording your first sale to see data here.</p>
                                        <a href="{{ route('inventory.sales.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-circle me-1"></i> Create First Sale
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 📄 Pagination --}}
        @if($sales->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $sales->firstItem() ?? 0 }} to {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }} results
                    </div>
                    {{ $sales->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- 🔍 Filter Modal --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Sales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date">
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method">
                            <option value="">All Methods</option>
                            <option value="cash">Cash</option>
                            <option value="pesapal">Online (Pesapal)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

// View Sale Details
function viewSale(saleId) {
    // Implement view functionality - could be a modal or separate page
    window.location.href = `/inventory/sales/${saleId}`;
}

// Export to CSV
function exportToCSV() {
    // Simple CSV export implementation
    const table = document.getElementById('salesTable');
    const rows = table.querySelectorAll('tr');
    let csv = [];
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Clean text content
            let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ");
            row.push('"' + text + '"');
        }
        
        csv.push(row.join(","));
    }

    // Download CSV file
    const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    const downloadLink = document.createElement("a");
    downloadLink.download = `sales-export-${new Date().toISOString().split('T')[0]}.csv`;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Apply Filters
function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData) {
        if (value) params.append(key, value);
    }
    
    window.location.href = `{{ route('inventory.sales.index') }}?${params.toString()}`;
}
</script>

<style>
.card {
    border: none;
    border-radius: 12px;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
}
</style>
@endpush