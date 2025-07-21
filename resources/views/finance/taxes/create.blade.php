@extends('finance.layouts.app')

@section('title', 'Register Tax Payment')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <h4 class="fw-bold text-primary mb-4">New Tax Payment</h4>

                <form method="POST" action="{{ route('finance.taxes.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tax Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rate (%)</label>
                            <input type="number" step="0.01" name="rate" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="active" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('finance.taxes.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Tax Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
