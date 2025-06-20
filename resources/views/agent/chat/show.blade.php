@extends('agent.layouts.app')
@section('title', 'Chat with ' . $user->name)

@section('content')
<div class="container py-3">
    <h4 class="mb-4 fw-bold">💬 Chat with {{ $user->name }}</h4>

    {{-- Chat Box --}}
    <div class="card shadow-sm mb-4" style="height: 400px; overflow-y: auto;" id="chat-box">
        <div class="card-body p-3" style="background: #f9f9f9;">
            @forelse ($messages as $msg)
                <div class="d-flex mb-3 {{ $msg->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="p-2 px-3 rounded {{ $msg->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light border' }}" style="max-width: 70%;">
                        <div>{{ $msg->message }}</div>
                        <div class="small mt-1 text-end {{ $msg->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                            {{ $msg->created_at->format('d M, H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">No messages yet. Say something!</p>
            @endforelse
        </div>
    </div>

    {{-- Message Form --}}
    <form method="POST" action="{{ route('agent.chat.send') }}">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $user->id }}">

        <div class="input-group">
            <textarea name="message" rows="1" class="form-control" placeholder="Type a message..." required></textarea>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to bottom on load
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
