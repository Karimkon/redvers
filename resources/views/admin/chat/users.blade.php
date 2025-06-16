@extends('admin.layouts.app')
@section('title', 'Chat Center')

@section('content')
<div class="container py-3">
    <h4 class="fw-bold mb-4">💬 Chat Center – Select a User to Chat</h4>

    @if($users->isEmpty())
        <div class="alert alert-warning">No users available to chat with.</div>
    @else
        <div class="list-group shadow-sm">
            @foreach ($users as $user)
                <a href="{{ route('admin.chat.index', $user->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-circle fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <strong>{{ $user->name }}</strong><br>
                            <small class="text-muted">{{ ucfirst($user->role) }}</small>
                        </div>
                    </div>
                    <span class="badge bg-primary rounded-pill">Chat</span>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
