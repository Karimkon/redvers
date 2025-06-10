@extends('admin.layouts.app')
@section('title', 'Chat with ' . $user->name)

@section('content')
<h4 class="mb-3">💬 Chat with {{ $user->name }}</h4>

<div class="card mb-3" style="max-height: 400px; overflow-y: auto;">
    <div class="card-body">
        @foreach ($messages as $msg)
            <div class="mb-2 text-{{ $msg->sender_id === auth()->id() ? 'end' : 'start' }}">
                <span class="badge bg-{{ $msg->sender_id === auth()->id() ? 'primary' : 'secondary' }}">
                    {{ $msg->message }}
                </span>
                <br>
                <small class="text-muted">{{ $msg->created_at->format('d M H:i') }}</small>
            </div>
        @endforeach
    </div>
</div>

<form method="POST" action="{{ route('admin.chat.send') }}">
    @csrf
    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
    <div class="mb-3">
        <textarea class="form-control" name="message" rows="2" placeholder="Type your message..." required></textarea>
    </div>
    <button class="btn btn-primary"><i class="bi bi-send"></i> Send</button>
</form>
@endsection
