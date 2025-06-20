<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Finance Dashboard')</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease-in-out;
        }
        .sidebar h3 {
            font-weight: bold;
            color: #ffffff;
            font-size: 1.35rem;
            text-align: center;
            margin-bottom: 2rem;
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
        .content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
            background-color: #f8fafc;
            transition: margin-left 0.3s ease-in-out;
        }
        .logout-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 6px;
            margin: 1rem auto;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
        .toggle-sidebar {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1050;
            background: #0d6efd;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
        }
        

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <button class="toggle-sidebar d-md-none">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div>
            <h3><i class="bi bi-cash-coin text-warning me-1"></i> Redvers Finance</h3>
            <a href="{{ route('finance.dashboard') }}" class="{{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('finance.reports') }}" class="{{ request()->is('finance/reports') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text me-2"></i> Reports
            </a>
            <a href="{{ route('finance.payments.index') }}" class="{{ request()->routeIs('finance.payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front me-2"></i> Payments
            </a>
            <a href="{{ route('finance.overdue.index') }}" class="{{ request()->routeIs('finance.overdue.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front me-2"></i> Overdue Payments
            </a>
            <a href="{{ route('finance.purchases.index') }}" class="{{ request()->routeIs('finance.purchases.*') ? 'active' : '' }}">
                <i class="bi bi-truck me-2"></i> Purchases
            </a>
            <a href="{{ route('finance.chat.users') }}" class="{{ request()->routeIs('finance.chat.*') ? 'active' : '' }}">
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

    <main class="content">
        @yield('content')
    </main>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.querySelector('.toggle-sidebar').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>

    @stack('scripts')
</body>
</html>
