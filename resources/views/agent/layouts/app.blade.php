<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Agent Dashboard')</title>
    <!-- CSS Select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111827;
            color: white;
            padding: 1.5rem 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: left 0.3s ease;
            z-index: 1050;
        }

        .sidebar h3 {
            font-weight: bold;
            font-size: 1.35rem;
            text-align: center;
            margin-bottom: 2rem;
            color: #ffffff;
        }

        .sidebar a {
            color: #9ca3af;
            display: flex;
            align-items: center;
            padding: 12px 24px;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #1f2937;
            color: #ffffff;
            border-left: 4px solid #0d6efd;
        }

        .logout-button {
            background-color: #dc3545;
            border: none;
            padding: 10px 24px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 6px;
            width: calc(100% - 48px);
            margin: 1rem auto;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
            background-color: #f8fafc;
            transition: margin-left 0.3s ease;
        }

        .mobile-header {
            background-color: #111827;
            color: white;
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            z-index: 1060;
        }

        .hamburger {
            font-size: 24px;
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1040;
        }

        .overlay.active {
            display: block;
        }

        .select2-container--bootstrap-5 .select2-selection {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 1rem;
            min-height: 38px;
            box-shadow: none;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #212529;
            font-weight: 500;
            padding-left: 0;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 0.75rem;
        }


        @media (max-width: 767.98px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
                padding: 1rem;
            }

            .mobile-header {
                display: flex;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header d-md-none">
        <i class="bi bi-list hamburger" onclick="toggleSidebar()"></i>
        <strong>Redvers Agent</strong>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="agentSidebar">
        <div>
            <h3 class="d-none d-md-block">
                <i class="bi bi-lightning-charge text-warning me-1"></i> Redvers Agent
            </h3>

            <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('agent.swaps.index') }}" class="{{ request()->routeIs('agent.swaps.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-battery-charging me-2"></i> New Swap
            </a>
            <a href="{{ route('agent.swap-history') }}" class="{{ request()->routeIs('agent.swap-history') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-clock-history me-2"></i> Swap History 
            </a>
            <a href="{{ route('agent.daily-payments.create') }}" class="{{ request()->routeIs('agent.daily-payment') ? 'active' : '' }}">
                <i class="bi bi-wallet2 me-2"></i> Rider Daily Payment
            </a>
            <a href="{{ route('agent.promotions.index') }}" class="{{ request()->routeIs('agent.promotions.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-stars me-2"></i> Rider Promotions
            </a>
            <a href="{{ route('agent.deliveries.index') }}" class="{{ request()->routeIs('agent.deliveries.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-truck-front-fill me-2"></i> My Battery Deliveries
            </a>
            <a href="{{ route('agent.chat.users') }}" class="{{ request()->routeIs('agent.chat.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots me-2"></i> Chat
            </a>

        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button" type="submit">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

    <!-- Sidebar overlay -->
    <div class="overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    <main class="content" id="mainContent">
        @yield('content')
    </main>

    <!-- Sidebar Toggle JS -->
    <script>
        function toggleSidebar() {
            document.getElementById('agentSidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function closeSidebar() {
            document.getElementById('agentSidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.0.2/dist/tesseract.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
    
    @push('scripts')
<script>
    $(document).ready(function () {
        $('#riderSelect').select2({
            placeholder: 'Search for a rider',
            allowClear: true,
            width: '100%'
        });

        // Retain battery assignment logic after rider is selected
        $('#riderSelect').on('change', function () {
            const selected = this.options[this.selectedIndex];
            $('#motorcycleDisplay').val(selected.getAttribute('data-bike-plate') || 'N/A');
            $('#motorcycleUnitId').val(selected.getAttribute('data-bike-id') || '');

            const batteryId = selected.getAttribute('data-battery-id');
            const batterySerial = selected.getAttribute('data-battery-serial');

            if (batteryId && batterySerial) {
                $('#batteryReturnedSelect').html(`<option value="${batteryId}" selected>${batterySerial}</option>`);
            } else {
                $('#batteryReturnedSelect').html(`<option value="">No battery found</option>`);
            }
        });
    });
</script>
@endpush

</body>
</html>
