@extends('finance.layouts.app')

@section('title', 'Edit Tax Record')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <h4 class="fw-bold text-primary mb-4">Edit Tax Record</h4>
                <form method="POST" action="{{ route('finance.taxes.update', $tax) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tax Type</label>
                            <input type="text" name="type" value="{{ old('type', $tax->type) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" value="{{ old('amount', $tax->amount) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" value="{{ old('date', $tax->date) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="bank" {{ $tax->payment_method == 'bank' ? 'selected' : '' }}>Bank</option>
                                <option value="petty_cash" {{ $tax->payment_method == 'petty_cash' ? 'selected' : '' }}>Petty Cash</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $tax->description) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('finance.taxes.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Tax Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
