<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body style="background: #f3f4f6">

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white p-3" style="min-height: 100vh; width: 250px;">
            <h5 class="text-white mb-4">🔧 Mechanic Panel</h5>

            <ul class="nav flex-column">
    <li class="nav-item mb-2">
        <a href="{{ route('mechanic.dashboard') }}" class="nav-link text-white {{ request()->routeIs('mechanic.dashboard') ? 'fw-bold' : '' }}">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('mechanic.maintenances.index') }}" class="nav-link text-white {{ request()->routeIs('mechanic.maintenances.*') ? 'fw-bold' : '' }}">
            <i class="bi bi-wrench-adjustable-circle me-1"></i>
            Maintenance Logs
        </a>
    </li>

        </nav>

        <!-- Main Content -->
        <main class="p-4 flex-fill">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>
</html>
