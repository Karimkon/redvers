@extends('admin.layouts.app')

@section('title', 'View Agent')

@section('content')
<h2>Agent Details</h2>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $agent->name }}</h5>
        
        <p class="card-text"><strong>Phone:</strong> {{ $agent->phone }}</p>
        <p class="card-text"><strong>Email:</strong> {{ $agent->email ?? 'N/A' }}</p>
        <p class="card-text"><strong>Station:</strong> {{ $agent->station->name ?? 'N/A' }}</p>
        
        <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
