@extends('agent.layouts.app')
@section('title', 'Chat Center')

@section('content')
<h4>💬 Select User to Chat</h4>
<ul class="list-group">
    @foreach ($users as $user)
        <li class="list-group-item d-flex justify-content-between">
            <span>{{ $user->name }} ({{ ucfirst($user->role) }})</span>
<a href="{{ route(Auth::user()->role . '.chat.index', $user->id) }}" class="btn btn-sm btn-outline-primary">Chat</a>

        </li>
    @endforeach
</ul>
@endsection
