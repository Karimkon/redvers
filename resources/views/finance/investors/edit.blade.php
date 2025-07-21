@extends('finance.layouts.app')

@section('title', 'Edit Investor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <h4 class="fw-bold text-primary mb-4">Edit Investor</h4>
                <form method="POST" action="{{ route('finance.investors.update', $investor) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $investor->name) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $investor->email) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $investor->phone) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contribution (UGX)</label>
                            <input type="number" name="contribution" value="{{ old('contribution', $investor->contribution) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ownership (%)</label>
                            <input type="number" name="ownership_percentage" value="{{ old('ownership_percentage', $investor->ownership_percentage) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="bank" {{ $investor->payment_method == 'bank' ? 'selected' : '' }}>Bank</option>
                                <option value="petty_cash" {{ $investor->payment_method == 'petty_cash' ? 'selected' : '' }}>Petty Cash</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" value="{{ old('date', $investor->date) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('finance.investors.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Investor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
