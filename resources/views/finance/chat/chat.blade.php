@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Chat with {{ \App\Models\User::find($userId)->name }}</h4>

    <div class="card mb-3">
        <div class="card-body" style="height: 300px; overflow-y: auto;">
            @foreach($messages as $msg)
                <div class="mb-2 {{ $msg->sender_id === auth()->id() ? 'text-end' : 'text-start' }}">
                    <span class="badge bg-{{ $msg->sender_id === auth()->id() ? 'primary' : 'secondary' }}">
                        {{ $msg->message }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <form method="POST" action="{{ route('chat.send') }}">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $userId }}">
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message...">
            <button class="btn btn-primary">Send</button>
        </div>
    </form>
</div>
@endsection
