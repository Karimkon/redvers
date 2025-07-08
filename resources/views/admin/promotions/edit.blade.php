@extends('admin.layouts.app')

@section('title', 'Edit Promotion')

@section('content')
<h3>Edit Promotion Status</h3>

<form action="{{ route('admin.promotions.update', $promotion) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="pending" @selected($promotion->status === 'pending')>Pending</option>
            <option value="active" @selected($promotion->status === 'active')>Active</option>
            <option value="expired" @selected($promotion->status === 'expired')>Expired</option>
            <option value="cancelled" @selected($promotion->status === 'cancelled')>Cancelled</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
