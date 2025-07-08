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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<style>
    body {
        background-color: #f1f5f9;
        font-family: 'Inter', sans-serif;
        margin: 0;
    }

    /* Sidebar with modern gradient + shadow */
    .sidebar {
        background: linear-gradient(180deg, #0f172a, #1e293b);
        color: white;
        height: 100vh;
        width: 270px;
        position: fixed;
        top: 40px;
        left: 0;
        z-index: 1050;
        padding-top: 1rem;
        padding-bottom: 1rem;
        overflow-y: auto;
        transition: left 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
    }

    .sidebar-content {
        flex: 1;
        overflow-y: auto;
    }

    .sidebar-footer {
        margin-top: auto;
        padding: 1rem 0;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar a {
        color: #9ca3af;
        display: flex;
        align-items: center;
        padding: 12px 20px;
        text-decoration: none;
        border-left: 4px solid transparent;
        transition: all 0.2s ease-in-out;
        font-size: 14px;
        font-weight: 500;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border-left-color: #3b82f6;
    }

    /* Accordion styling */
    .accordion-item {
        background: transparent !important;
        border: none !important;
        margin-bottom: 2px;
    }

    .accordion-button {
        background: transparent !important;
        color: #9ca3af !important;
        border: none !important;
        box-shadow: none !important;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        border-left: 4px solid transparent;
        transition: all 0.2s ease-in-out;
    }

    .accordion-button:hover,
    .accordion-button:not(.collapsed) {
        background: rgba(255,255,255,0.1) !important;
        color: #fff !important;
        border-left-color: #3b82f6;
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        filter: brightness(0.8);
    }

    .accordion-button:not(.collapsed)::after {
        filter: brightness(1);
    }

    .accordion-collapse {
        background: rgba(0,0,0,0.2);
    }

    .accordion-body {
        padding: 0;
    }

    .accordion-body a {
        padding: 10px 20px 10px 45px;
        color: #9ca3af;
        text-decoration: none;
        font-size: 13px;
        display: block;
        border-left: 4px solid transparent;
        transition: all 0.2s ease-in-out;
    }

    .accordion-body a:hover,
    .accordion-body a.active {
        background-color: rgba(255,255,255,0.1);
        color: white;
        border-left-color: #3b82f6;
    }

    .logout-button {
        background-color: #dc3545;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
        margin: 0 20px;
        width: calc(100% - 40px);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .logout-button:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }

    .content {
        margin-left: 270px;
        padding: 2rem;
        margin-top: 40px;
        transition: margin-left 0.3s ease;
    }

    /* Mobile header */
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

    /* Bell animation */
    .bell-shake {
        animation: shake 1.2s ease-in-out infinite;
        transform-origin: top center;
        filter: drop-shadow(0 0 6px rgba(255,193,7,0.7));
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

    /* Metric + battery cards: glassmorphism + animation */
    .card {
        border-radius: 1rem;
        background: linear-gradient(135deg, rgba(255,255,255,0.4), rgba(255,255,255,0.2));
        backdrop-filter: blur(12px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        color: #1f2937;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* .card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 12px 32px rgba(0,0,0,0.2);
    } */

    /* Hero logo animation */
    .dashboard-logo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid #3b82f6;
        animation: pulseLogo 2s infinite;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    @keyframes pulseLogo {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.85; transform: scale(1.1); }
    }

    /* Buttons with gradient */
    .btn-gradient {
        background: linear-gradient(135deg, #0d6efd, #3b82f6);
        color: white;
        border: none;
        border-radius: 0.8rem;
    }

    /* Alerts with glass effect */
    .alert-glass, .alert-glass-info {
        backdrop-filter: blur(8px);
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        padding: 1rem;
        font-weight: 600;
    }

    .alert-glass {
        background: rgba(34,197,94,0.15);
        color: #14532d;
    }

    .alert-glass-info {
        background: rgba(59,130,246,0.15);
        color: #1e3a8a;
    }

    /* Charts container */
    .chart-card {
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        background: white;
    }

    .chart-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        font-weight: 600;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    /* Form controls in filter bar */
    .dashboard-filter .form-control,
    .dashboard-filter .form-select {
        border-radius: 0.8rem;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Logo styling */
    .sidebar-logo {
        text-align: center;
        font-weight: bold;
        color: white;
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        padding: 0 20px;
    }

    .sidebar-logo i {
        margin-right: 8px;
        color: #3b82f6;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            left: -270px;
            top: 40px;
            backdrop-filter: blur(12px);
            background-color: rgba(31,41,55,0.95);
            transition: left 0.4s ease, backdrop-filter 0.4s ease;
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
        <div class="sidebar-content">
            <!-- Logo -->
            <div class="sidebar-logo">
                <i class="bi bi-lightning-fill"></i> Redvers Admin
            </div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>


            <!-- Stations (standalone) -->
            <a href="{{ route('admin.stations.index') }}" class="{{ request()->routeIs('admin.stations.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill me-2"></i> Stations
            </a>

            <!-- Accordion Groups -->
            <div class="accordion" id="adminModules">

            <!-- Battery Swaps Group -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#batterySwapsCollapse"
                            aria-expanded="false" aria-controls="batterySwapsCollapse">
                        <i class="bi bi-battery-charging me-2"></i> Battery Swaps
                    </button>
                </h2>
                <div id="batterySwapsCollapse"
                    class="accordion-collapse collapse {{ request()->is('admin/swaps*') || request()->is('admin/payments*') || request()->is('admin/promotions*') ? 'show' : '' }}">
                    <div class="accordion-body">
                        <a href="{{ route('admin.swaps.index') }}" class="{{ request()->routeIs('admin.swaps.*') ? 'active' : '' }}">
                            <i class="bi bi-battery me-2"></i> All Swaps
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card me-2"></i> Swap Payments
                        </a>
                        <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                            <i class="bi bi-stars me-2"></i> Swap Promotions
                        </a>
                    </div>
                </div>
            </div>

                <!-- User Administration -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#userAdminCollapse" aria-expanded="false" aria-controls="userAdminCollapse">
                            <i class="bi bi-people-fill me-2"></i> User Administration
                        </button>
                    </h2>
                    <div id="userAdminCollapse" class="accordion-collapse collapse {{ request()->is('admin/riders*') || request()->is('admin/agents*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <a href="{{ route('admin.riders.index') }}" class="{{ request()->routeIs('admin.riders.*') ? 'active' : '' }}">
                                <i class="bi bi-person-fill me-2"></i> Riders
                            </a>
                            <a href="{{ route('admin.agents.index') }}" class="{{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
                                <i class="bi bi-person-badge-fill me-2"></i> Agents
                            </a>
                            <a href="{{ route('admin.inventory.index') }}" class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                                <i class="bi bi-person-workspace me-2"></i> Inventory Operators
                            </a>
                            <a href="{{ route('admin.finance.index') }}" class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i> Finance Staff
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Battery Management -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#batteryMgmtCollapse" aria-expanded="false" aria-controls="batteryMgmtCollapse">
                            <i class="bi bi-battery me-2"></i> Battery Management
                        </button>
                    </h2>
                    <div id="batteryMgmtCollapse" class="accordion-collapse collapse {{ request()->is('admin/batteries*') || request()->is('admin/deliveries*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <a href="{{ route('admin.batteries.index') }}" class="{{ request()->routeIs('admin.batteries.*') ? 'active' : '' }}">
                                <i class="bi bi-battery-full me-2"></i> All Batteries
                            </a>
                            <a href="{{ route('admin.deliveries.index') }}" class="{{ request()->routeIs('admin.deliveries.index') ? 'active' : '' }}">
                                <i class="bi bi-truck-front-fill me-2"></i> Battery Deliveries
                            </a>
                            <a href="{{ route('admin.deliveries.returns') }}" class="{{ request()->routeIs('admin.deliveries.returns') ? 'active' : '' }}">
                                <i class="bi bi-arrow-return-left me-2"></i> Returned Batteries
                            </a>
                            <a href="{{ route('admin.deliveries.history') }}" class="{{ request()->routeIs('admin.deliveries.history') ? 'active' : '' }}">
                                <i class="bi bi-clock-history me-2"></i> Battery History
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bike Management -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bikeMgmtCollapse" aria-expanded="false" aria-controls="bikeMgmtCollapse">
                            <i class="bi bi-bicycle me-2"></i> Bike Management
                        </button>
                    </h2>
                    <div id="bikeMgmtCollapse" class="accordion-collapse collapse {{ request()->is('admin/purchases*') || request()->is('admin/motorcycle-units*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <a href="{{ route('admin.purchases.index') }}" class="{{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
                                <i class="bi bi-truck me-2"></i> Bike Purchases
                            </a>
                            <a href="{{ route('admin.motorcycle-units.index') }}" class="{{ request()->routeIs('admin.motorcycle-units.*') ? 'active' : '' }}">
                                <i class="bi bi-hash me-2"></i> Motorcycle Units
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Wallet Module -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#walletModuleCollapse"
                                aria-expanded="false" aria-controls="walletModuleCollapse">
                            <i class="bi bi-wallet2 me-2"></i> Wallet Module
                        </button>
                    </h2>
                    <div id="walletModuleCollapse"
                        class="accordion-collapse collapse {{ request()->is('admin/wallets*') ? 'show' : '' }}">
                        <div class="accordion-body">

                            <!-- Wallet list (index) -->
                            <a href="{{ route('admin.wallets.index') }}"
                            class="{{ request()->routeIs('admin.wallets.index') ? 'active' : '' }}">
                                <i class="bi bi-wallet me-2"></i> Wallets
                            </a>
                        </div>
                    </div>
                </div>



                <!-- Inventory Module -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#inventoryCollapse" aria-expanded="false" aria-controls="inventoryCollapse">
                            <i class="bi bi-boxes me-2"></i> Inventory Module
                        </button>
                    </h2>
                    <div id="inventoryCollapse" class="accordion-collapse collapse {{ request()->is('admin/inventory*') || request()->is('admin/shops*') || request()->is('admin/spares*') || request()->is('admin/low-stock-alerts*') || request()->is('admin/parts*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <a href="{{ route('admin.spares.dashboard') }}" class="{{ request()->routeIs('admin.spares.*') ? 'active' : '' }}">
                                <i class="bi bi-tools me-2"></i> Spare Dashboard
                            </a>
                            <a href="{{ route('admin.shops.index') }}" class="{{ request()->routeIs('admin.shops.*') ? 'active' : '' }}">
                                <i class="bi bi-shop-window me-2"></i> Shops
                            </a>
                            <a href="{{ route('admin.parts.index') }}" class="{{ request()->routeIs('admin.parts.*') ? 'active' : '' }}">
                                <i class="bi bi-gear me-2"></i> All Parts
                            </a>
                            <a href="{{ route('admin.low-stock-alerts.index') }}" class="{{ request()->routeIs('admin.low-stock-alerts.*') ? 'active' : '' }}">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Low Stock Alerts
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat (standalone) -->
            <a href="{{ route('admin.chat') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots me-2"></i> Chat
            </a>
        </div>

        <!-- Sidebar Footer with Logout -->
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-button" type="submit">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    <main class="content" id="mainContent">
        @yield('content')
        <!-- 🔍 Quick Part Lookup Modal -->
<div class="modal fade" id="partLookupModal" tabindex="-1"
     aria-labelledby="partLookupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="partLookupModalLabel">Quick Part Lookup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control mb-3" id="partSearchInput"
               placeholder="Search parts by name...">
        <ul class="list-group" id="partList">
          <li class="list-group-item text-muted text-center">
            Start typing to search parts...
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

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

document.addEventListener('DOMContentLoaded', () => {
    const $input = $('#partSearchInput');
    const $list  = $('#partList');

    function fetchParts(q = '') {
        $.get('{{ route('admin.api.lookup') }}', { q })
         .done(parts => {
             if (!parts.length) {
                 $list.html(
                     '<li class="list-group-item text-center text-muted">No parts found.</li>'
                 );
                 return;
             }
             const items = parts.map(p => `
            <li class="list-group-item p-0">
                <a href="${p.edit_url}" class="d-flex justify-content-between align-items-start text-decoration-none p-3" style="color:inherit;">
                    <div class="me-2">
                        <strong>${escapeHtml(p.name)}</strong><br>
                        <small class="text-muted">
                            Shop: ${escapeHtml(p.shop)} &middot; Stock: ${p.stock}
                        </small>
                    </div>
                    <span class="badge bg-success mt-1">
                        UGX ${Number(p.price).toLocaleString()}
                    </span>
                </a>
            </li>
            `).join('');

             $list.html(items);
             $('#partList a').on('click', () => $('#partLookupModal').modal('hide'));

         })
         .fail(() => {
             $list.html(
                 '<li class="list-group-item text-danger text-center">Error fetching parts.</li>'
             );
         });
    }

    // Simple HTML‑escaper to avoid XSS
    function escapeHtml(str) {
        return str.replace(/[&<>'"]/g, t => ({
            '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
        }[t]));
    }

    // Load default list when modal opens
    $('#partLookupModal').on('shown.bs.modal', () => {
        fetchParts();
        $input.val('').trigger('focus');
    });

    // Debounced live search
    let debounce;
    $input.on('input', () => {
        clearTimeout(debounce);
        debounce = setTimeout(() => fetchParts($input.val().trim()), 300);
    });
});
    </script>

    @stack('scripts')
</body>
</html>