@extends('finance.layouts.app')

@section('title', 'All Tax Settings')

@section('content')
<div class="container-fluid py-3">
    <h4 class="mb-4 fw-bold text-primary">💰 Tax Settings</h4>

    <div class="mb-3 text-end">
        <a href="{{ route('finance.taxes.create') }}" class="btn btn-success btn-sm">➕ Add Tax Setting</a>
    </div>

    @if($taxes->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Rate (%)</th>
                        <th>Status</th>
                        <th>Attachment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($taxes as $tax)
                        <tr>
                            <td>{{ $tax->name }}</td>
                            <td>{{ number_format($tax->rate, 2) }}%</td>
                            <td>
                                @if($tax->active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($tax->attachment)
                                <a href="{{ asset('storage/' . $tax->attachment->file_path) }}" target="_blank">📎 View File</a>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('finance.taxes.edit', $tax) }}" class="btn btn-sm btn-warning">✏️</a>
                                <form action="{{ route('finance.taxes.destroy', $tax) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tax setting?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $taxes->links() }}
        </div>
    @else
        <div class="alert alert-info">No tax settings found.</div>
    @endif
</div>
@endsection
