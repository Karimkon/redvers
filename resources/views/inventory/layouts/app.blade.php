<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Inventory Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .sidebar {
            background-color: #1f2937;
            color: white;
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            transition: left 0.3s ease;
            z-index: 1050;
            padding-top: 1rem;
            overflow-y: auto;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar a {
            color: #9ca3af;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.2s ease-in-out;
            font-size: 15px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #374151;
            color: #fff;
            border-left: 4px solid #10b981;
        }

        .logout-button {
            background-color: #dc3545;
            border: none;
            padding: 6px 14px;
            font-size: 14px;
            margin: 1rem 20px;
            width: calc(100% - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            border-radius: 4px;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .content {
            transition: margin-left 0.3s ease;
            padding: 2rem;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .overlay.active {
            display: block;
        }

        .sidebar h3 {
            color: white;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .sidebar {
                left: 0;
            }

            .overlay {
                display: none !important;
            }

            .content {
                margin-left: 250px;
            }

            .mobile-header {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .content {
                margin-left: 0 !important;
                padding: 1rem;
            }
        }

        .hamburger {
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px;
        }

        .mobile-header {
            background-color: #1f2937;
            color: white;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            justify-content: space-between;
            z-index: 1051;
            position: relative;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Mobile Header -->
<div class="mobile-header d-md-none">
    <i class="bi bi-list hamburger" onclick="toggleSidebar()"></i>
    <strong>Redvers Mobility</strong>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h3>Redvers Stock Management</h3>
<!-- Sidebar Navigation -->
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('inventory.dashboard') ? 'active' : '' }}" href="{{ route('inventory.dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('inventory.parts.*') ? 'active' : '' }}" href="{{ route('inventory.parts.index') }}">
            <i class="bi bi-tools me-2"></i> Parts
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('inventory.stock-entries.*') ? 'active' : '' }}" href="{{ route('inventory.stock-entries.index') }}">
            <i class="bi bi-box-arrow-down me-2"></i> Stock Entries
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('inventory.sales.*') ? 'active' : '' }}" href="{{ route('inventory.sales.index') }}">
            <i class="bi bi-cart-check me-2"></i> Sales
        </a>
    </li>
</ul>

<form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-button">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>

<!-- Overlay -->
<div class="overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Main Content -->
<main class="content" id="mainContent">
    <!-- Topbar -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('inventory.profile') }}">
            <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                 alt="Profile" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
        </a>
    </div>

    @yield('content')
</main>

<!-- Scripts -->
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('active');
        document.getElementById('sidebarOverlay').classList.remove('active');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')
</body>
</html>
