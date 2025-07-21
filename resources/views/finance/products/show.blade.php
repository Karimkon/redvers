@extends('finance.layouts.app')
@section('title', 'Product Details')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-3">Product Details</h4>

        <dl class="row">
            <dt class="col-sm-3">Name</dt>
            <dd class="col-sm-9">{{ $product->name }}</dd>

            <dt class="col-sm-3">Category</dt>
            <dd class="col-sm-9">{{ $product->category?->name ?? 'N/A' }}</dd>

            <dt class="col-sm-3">Unit Cost</dt>
            <dd class="col-sm-9">UGX {{ number_format($product->unit_cost) }}</dd>

            <dt class="col-sm-3">Description</dt>
            <dd class="col-sm-9">{{ $product->description }}</dd>

            <dt class="col-sm-3">Created At</dt>
            <dd class="col-sm-9">{{ $product->created_at->format('d M Y') }}</dd>
        </dl>

        <div class="text-end">
            <a href="{{ route('finance.products.edit', $product) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('finance.products.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
