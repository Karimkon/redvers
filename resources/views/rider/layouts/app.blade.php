<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Rider Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1f2937;
            color: white;
            padding: 1rem 0;
            overflow-y: auto;
        }
        .sidebar h3 {
            font-weight: bold;
            text-align: center;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .sidebar a {
            color: #9ca3af;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #374151;
            color: #fff;
            border-left: 4px solid #10b981;
        }
        .content {
            margin-left: 230px;
            padding: 2rem;
        }
        .logout-button {
            background-color: #ef4444;
            border: none;
            color: white;
            padding: 8px 20px;
            margin: 20px;
            width: calc(100% - 40px);
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            display: block;
        }
        .logout-button:hover {
            background-color: #dc2626;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="sidebar">
    <h3>Redvers Rider</h3>

    <a href="{{ route('rider.dashboard') }}" class="{{ request()->routeIs('rider.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    <a href="{{ route('rider.swaps') }}" class="{{ request()->routeIs('rider.swaps') ? 'active' : '' }}">
    <i class="bi bi-arrow-left-right me-2"></i> My Swaps
    </a>

    {{-- Future navigation items can go here --}}

    <a href="{{ route('rider.payments.index') }}" class="{{ request()->routeIs('rider.payments.*') ? 'active' : '' }}">
        <i class="bi bi-cash-coin me-2"></i>My Payments
    </a>

    <a href="{{ route('rider.stations') }}" class="{{ request()->routeIs('rider.stations') ? 'active' : '' }}">
    <i class="bi bi-geo-alt-fill me-2"></i> Nearby Stations
    </a>


    <a href="{{ route('rider.profile') }}" class="{{ request()->routeIs('rider.profile') ? 'active' : '' }}">
        <i class="bi bi-person-circle me-2"></i> Profile
    </a>

    <a href="{{ route('rider.chat') }}" class="{{ request()->routeIs('rider.chat.*') ? 'active' : '' }}">
        <i class="bi bi-chat-dots me-2"></i> Chat
    </a>



    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-button">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>

<main class="content">
    <!-- Topbar with avatar -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('rider.profile') }}">
            <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                 alt="Profile" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
        </a>
    </div>

    @yield('content')
</main>

<!-- Bootstrap & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@stack('scripts')
</body>
</html>
