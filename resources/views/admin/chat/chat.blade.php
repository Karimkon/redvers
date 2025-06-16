@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">💬 Chat with {{ \App\Models\User::find($userId)->name }}</h4>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    {{-- Chat Box --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body px-4 py-3" style="height: 400px; overflow-y: auto; background: #f8f9fa;">
            @forelse($messages as $msg)
                <div class="d-flex mb-2 {{ $msg->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="p-2 rounded text-white {{ $msg->sender_id === auth()->id() ? 'bg-primary' : 'bg-secondary' }}" style="max-width: 70%;">
                        {{ $msg->message }}
                        <div class="small text-white-50 mt-1 text-end">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No messages yet. Start the conversation!</p>
            @endforelse
        </div>
    </div>

    {{-- Message Form --}}
    <form method="POST" action="{{ route('chat.send') }}">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $userId }}">
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message..." required autofocus>
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </form>
</div>
@endsection
