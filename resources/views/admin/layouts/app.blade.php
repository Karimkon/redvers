<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f8f9fa;
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
            border-left: 4px solid #0d6efd;
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
        <strong>Redvers Admin</strong>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
       <div class="text-center fw-bold text-white fs-5 mb-4">
            <i class="bi bi-lightning-fill me-1"></i> Redvers Admin
        </div>

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.riders.index') }}" class="{{ request()->routeIs('admin.riders.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-people-fill me-2"></i> Riders
        </a>
        <a href="{{ route('admin.swaps.index') }}" class="{{ request()->routeIs('admin.swaps.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-battery-charging me-2"></i> Swaps
        </a>
        <a href="{{ route('admin.batteries.index') }}" class="{{ request()->routeIs('batteries.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-battery me-2"></i> Batteries
        </a>
        <a href="{{ route('admin.agents.index') }}" class="{{ request()->routeIs('admin.agents.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-person-badge-fill me-2"></i> Agents
        </a>
        <a href="{{ route('admin.stations.index') }}" class="{{ request()->routeIs('admin.stations.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-geo-alt-fill me-2"></i> Stations
        </a>
        <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-credit-card me-2"></i> Swap Payments
        </a>
        <a href="{{ route('admin.purchases.index') }}" class="{{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-truck me-2"></i> Bike Purchases
        </a>
        <a href="{{ route('admin.motorcycle-units.index') }}" class="{{ request()->routeIs('admin.motorcycle-units.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-hash me-2"></i> Motorcycle Units
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button" type="submit">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

    <!-- Sidebar overlay -->
    <div class="overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Content -->
    <main class="content" id="mainContent">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.0.2/dist/tesseract.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
</body>
</html>
