@extends('finance.layouts.app')

@section('title', 'Register Investor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-primary">Investor Registration</h4>
                </div>

                <form method="POST" action="{{ route('finance.investors.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email (optional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number (optional)</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contribution Amount (UGX)</label>
                            <input type="number" name="contribution" value="{{ old('contribution') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ownership Percentage (%)</label>
                            <input type="number" step="0.01" name="ownership_percentage" value="{{ old('ownership_percentage') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">-- Choose --</option>
                                <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank</option>
                                <option value="petty_cash" {{ old('payment_method') == 'petty_cash' ? 'selected' : '' }}>Petty Cash</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Contribution</label>
                            <input type="date" name="date" value="{{ old('date') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Attach Document (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('finance.investors.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Investor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
