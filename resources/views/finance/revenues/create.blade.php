@extends('finance.layouts.app')

@section('title', 'Record Revenue')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <h4 class="fw-bold text-primary mb-4">New Revenue Entry</h4>
                <form method="POST" action="{{ route('finance.revenues.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Source</label>
                            <input type="text" name="source" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (UGX)</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Received</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="bank">Bank</option>
                                <option value="petty_cash">Petty Cash</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reference (optional)</label>
                            <input type="text" name="reference" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('finance.revenues.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Revenue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
