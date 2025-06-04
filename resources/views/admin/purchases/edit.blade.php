@extends('admin.layouts.app')

@section('title', 'Edit Purchase')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Purchase ({{ $purchase->user->name }})</h4>

    <form action="{{ route('admin.purchases.update', $purchase->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ $purchase->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ $purchase->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="defaulted" {{ $purchase->status == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Update Purchase</button>
    </form>
</div>
@endsection
