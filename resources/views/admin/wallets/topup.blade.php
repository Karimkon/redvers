@extends('admin.layouts.app')

@section('title', 'Top‑Up Wallet')

@section('content')
<div class="container" style="max-width:640px">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold mb-3 text-primary">
            <i class="bi bi-plus-circle me-1"></i>Top‑Up Wallet
        </h4>

        <p class="mb-1"><strong>Rider:</strong> {{ $user->name }} ({{ $user->phone ?? 'N/A' }})</p>
        <p class="text-muted">Current balance: UGX {{ number_format($user->wallet->balance ?? 0) }}</p>

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.wallets.topup.store', $user) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (UGX)</label>
                <input type="number" id="amount" name="amount" min="1000" class="form-control" 
                       value="{{ old('amount') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note (optional)</label>
                <textarea id="note" name="reason" rows="2" class="form-control">{{ old('reason') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i>Credit
                </button>
                <a href="{{ route('admin.wallets.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
