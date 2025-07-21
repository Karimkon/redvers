@extends('finance.layouts.app')

@section('title', 'Edit Revenue')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <h4 class="fw-bold text-primary mb-4">Edit Revenue Entry</h4>
                <form method="POST" action="{{ route('finance.revenues.update', $revenue) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Source</label>
                            <input type="text" name="source" value="{{ old('source', $revenue->source) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" value="{{ old('amount', $revenue->amount) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" value="{{ old('date', $revenue->date) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="bank" {{ $revenue->payment_method == 'bank' ? 'selected' : '' }}>Bank</option>
                                <option value="petty_cash" {{ $revenue->payment_method == 'petty_cash' ? 'selected' : '' }}>Petty Cash</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reference</label>
                            <input type="text" name="reference" value="{{ old('reference', $revenue->reference) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $revenue->description) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('finance.revenues.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Revenue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
