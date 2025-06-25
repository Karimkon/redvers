@extends('inventory.layouts.app')
@section('title', 'My Profile')
@section('content')
    <div class="container py-4">
        <h4>My Profile</h4>
        <p>Name: {{ auth()->user()->name }}</p>
        <p>Email: {{ auth()->user()->email ?? '—' }}</p>
        <p>Phone: {{ auth()->user()->phone }}</p>
        <p>Shop: {{ auth()->user()->shop->name ?? 'Unassigned' }}</p>
    </div>
@endsection
