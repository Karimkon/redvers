<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

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
            top: 40px;
            left: 0;
            z-index: 1050;
            padding-top: 1rem;
            overflow-y: auto;
            transition: left 0.3s ease;
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
            margin-left: 250px;
            padding: 2rem;
            margin-top: 40px;
            transition: margin-left 0.3s ease;
        }

        .mobile-header {
            background-color: #1f2937;
            color: white;
            display: none;
            align-items: center;
            padding: 0.75rem 1rem;
            justify-content: space-between;
            z-index: 1051;
            position: fixed;
            top: 40px;
            left: 0;
            right: 0;
        }

        .hamburger {
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px;
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

        .bell-shake {
            animation: shake 1.2s ease-in-out infinite;
            transform-origin: top center;
        }

        .accordion-button {
            background-color: #1f2937;
            color: #f8f9fa;
            box-shadow: none;
        }

        .accordion-button:not(.collapsed) {
            background-color: #0d6efd;
            color: white;
        }

        .accordion-body a {
            display: block;
            padding: 10px 20px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
        }

        .accordion-body a:hover,
        .accordion-body a.active {
            background-color: #374151;
            color: white;
        }


        @keyframes shake {
            0% { transform: rotate(0); }
            15% { transform: rotate(10deg); }
            30% { transform: rotate(-10deg); }
            45% { transform: rotate(10deg); }
            60% { transform: rotate(-10deg); }
            75% { transform: rotate(5deg); }
            100% { transform: rotate(0); }
        }

        @media (max-width: 767.98px) {
            .sidebar {
                left: -250px;
                top: 40px;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0 !important;
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
    @php
        $hasUnread = true; // Replace this with actual unread message detection logic
    @endphp

    <!-- 🔔 Notification Bar -->
    <div style="height: 40px; background: #1f2937; position: fixed; top: 0; right: 0; left: 0; display: flex; justify-content: flex-end; align-items: center; padding: 0 20px; z-index: 1060; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <div class="dropdown">
            <a href="#" class="position-relative text-decoration-none" id="notificationBell"
            data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill fs-5 text-warning" id="bellIcon"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notificationBadge">0</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="width: 300px;">
                <li class="dropdown-header">Unread Messages</li>
                <div id="notificationList" class="px-2 py-1 small text-dark">No messages yet</div>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center" href="{{ route('admin.chat') }}">Go to Chat</a></li>
            </ul>
        </div>
    </div>

    <!-- 📱 Mobile Header -->
    <div class="mobile-header d-md-none">
        <i class="bi bi-list hamburger" onclick="toggleSidebar()"></i>
        <strong>Redvers Admin</strong>
    </div>

    <!-- 📚 Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="text-center fw-bold text-white fs-5 mb-4">
            <i class="bi bi-lightning-fill me-1"></i> Redvers Admin
        </div>

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.riders.index') }}" class="{{ request()->routeIs('admin.riders.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill me-2"></i> Riders
        </a>
        <a href="{{ route('admin.swaps.index') }}" class="{{ request()->routeIs('admin.swaps.*') ? 'active' : '' }}">
            <i class="bi bi-battery-charging me-2"></i> Swaps
        </a>
        <a href="{{ route('admin.batteries.index') }}" class="{{ request()->routeIs('admin.batteries.*') ? 'active' : '' }}">
            <i class="bi bi-battery me-2"></i> Batteries
        </a>
        <a href="{{ route('admin.deliveries.index') }}" class="{{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}">
            <i class="bi bi-truck-front-fill me-2"></i> Battery Deliveries
        </a>
        <a href="{{ route('admin.deliveries.returns') }}" class="{{ request()->routeIs('admin.deliveries.returns') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-left me-2"></i> Returned Batteries
        </a>
        <a href="{{ route('admin.deliveries.history') }}" class="{{ request()->routeIs('admin.deliveries.history') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> Batteries History
        </a>
        <a href="{{ route('admin.agents.index') }}" class="{{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill me-2"></i> Agents
        </a>
        <a href="{{ route('admin.stations.index') }}" class="{{ request()->routeIs('admin.stations.*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt-fill me-2"></i> Stations
        </a>
        <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card me-2"></i> Swap Payments
        </a>
        <a href="{{ route('admin.purchases.index') }}" class="{{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
            <i class="bi bi-truck me-2"></i> Bike Purchases
        </a>
        <a href="{{ route('admin.motorcycle-units.index') }}" class="{{ request()->routeIs('admin.motorcycle-units.*') ? 'active' : '' }}">
            <i class="bi bi-hash me-2"></i> Motorcycle Units
        </a>

        <div class="accordion" id="adminModules">
    <!-- Inventory Module Group -->
    <div class="accordion-item bg-transparent border-0">
        <h2 class="accordion-header">
            <button class="accordion-button bg-dark text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#inventoryCollapse" aria-expanded="false" aria-controls="inventoryCollapse">
                <i class="bi bi-boxes me-2"></i> Inventory Module
            </button>
        </h2>
        <div id="inventoryCollapse" class="accordion-collapse collapse {{ request()->is('admin/inventory*') || request()->is('admin/shops*') || request()->is('admin/spares*') || request()->is('admin/low-stock-alerts*') ? 'show' : '' }}">
            <div class="accordion-body p-0">
                <a href="{{ route('admin.spares.dashboard') }}" class="ps-4 {{ request()->routeIs('admin.spares.*') ? 'active' : '' }}">
                    <i class="bi bi-tools me-2"></i> Spare Dashboard
                </a>
                <a href="{{ route('admin.shops.index') }}" class="ps-4 {{ request()->routeIs('admin.shops.*') ? 'active' : '' }}">
                    <i class="bi bi-shop-window me-2"></i> Shops
                </a>
                <a href="{{ route('admin.parts.index') }}" class="{{ request()->routeIs('admin.parts.*') ? 'active' : '' }}">
                    <i class="bi bi-gear me-2"></i> All Parts
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="ps-4 {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-person-workspace me-2"></i> Inventory Operators
                </a>
                <a href="{{ route('admin.low-stock-alerts.index') }}" class="ps-4 {{ request()->routeIs('admin.low-stock-alerts.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Low Stock Alerts
                </a>
            </div>
        </div>
    </div>
</div>


        <a href="{{ route('admin.chat') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots me-2"></i> Chat
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button" type="submit">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    <main class="content" id="mainContent">
        @yield('content')
    </main>

 <!-- 🔈 Audio -->
<audio id="notificationSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.0.2/dist/tesseract.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('sidebarOverlay').classList.toggle('active');
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('active');
    document.getElementById('sidebarOverlay').classList.remove('active');
}

// ✅ Smart Notification Polling
document.addEventListener('DOMContentLoaded', function () {
    const bellIcon = document.getElementById('bellIcon');
    const badge = document.getElementById('notificationBadge');
    const list = document.getElementById('notificationList');
    const sound = document.getElementById('notificationSound');
    const bellLink = document.getElementById('notificationBell');

    let lastCount = parseInt(localStorage.getItem('lastNotificationCount') || '0', 10);
    let dropdownOpen = false;
    let soundCooldown = false;

    // Detect if on chat page
    function isOnChatPage() {
        return window.location.href.includes('/admin/chat');
    }

    // Detect if dropdown was recently opened
    function isDropdownOpen() {
        return dropdownOpen;
    }

    // Bell click handler
    bellLink.addEventListener('click', function () {
        dropdownOpen = true;
        localStorage.setItem('lastNotificationCount', '0');
        lastCount = 0;

        setTimeout(() => {
            dropdownOpen = false;
        }, 10000); // reset after 10 sec
    });

    // Fetch and update notifications
    function fetchNotifications() {
        fetch('{{ route('admin.notifications') }}')
            .then(res => res.json())
            .then(data => {
                const count = data.count;

                if (count > 0) {
                    badge.textContent = count;
                    badge.classList.remove('d-none');
                    bellIcon.classList.add('bell-shake');

                    // 🔊 Play sound and vibrate if new
                    if (count > lastCount && !isDropdownOpen() && !isOnChatPage() && !soundCooldown) {
                        try { sound.play(); } catch (e) {}
                        if ('vibrate' in navigator) navigator.vibrate(500);

                        soundCooldown = true;
                        setTimeout(() => { soundCooldown = false; }, 15000);
                    }

                    // Render messages
                        list.innerHTML = data.messages.map(msg => `
                        <div class="py-1 border-bottom">
                            <a href="/admin/chat/${msg.user_id}#msg-${msg.id}" class="text-decoration-none text-dark d-block">
                                <strong>${msg.sender}</strong><br>
                                <small>${msg.text}</small>
                            </a>
                        </div>
                    `).join('');


                } else {
                    badge.classList.add('d-none');
                    bellIcon.classList.remove('bell-shake');
                    list.innerHTML = '<div class="text-muted px-2 py-2">No new messages</div>';
                }

                localStorage.setItem('lastNotificationCount', count);
                lastCount = count;
            })
            .catch(err => {
                console.error("Notification fetch failed:", err);
            });
    }

    fetchNotifications();
    setInterval(fetchNotifications, 10000); // every 10 seconds
});
</script>

@stack('scripts')
</body>
</html>