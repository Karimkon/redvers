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
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111827;
            color: white;
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            z-index: 1040;
        }
        .sidebar h3 {
            font-size: 1.3rem;
            text-align: center;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        .sidebar .nav-link {
            color: #9ca3af;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #1f2937;
            color: white;
            border-left: 4px solid #0d6efd;
        }
        .sidebar .accordion-button {
            background-color: transparent;
            color: #9ca3af;
            font-size: 15px;
            padding-left: 24px;
        }
        .sidebar .accordion-button:not(.collapsed),
        .sidebar .accordion-button:hover {
            background-color: #1f2937;
            color: white;
        }
        .sidebar .accordion-body {
            padding: 0;
        }
        .sidebar .accordion-item {
            background-color: transparent;
            border: none;
        }
        .content {
            margin-left: 260px;
            padding: 2rem;
            background-color: #f8fafc;
            min-height: 100vh;
        }
        .logout-button {
            background-color: #dc3545;
            border: none;
            width: 90%;
            margin: 1rem auto;
            padding: 10px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            gap: 8px;
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
                transition: transform 0.3s ease-in-out;
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
    @php
    $isAssetsActive = request()->routeIs('finance.product_categories.*') || request()->routeIs('finance.products.*') || request()->routeIs('finance.cogs.*');
    $isFinancialsActive = request()->routeIs('finance.revenues.*') || request()->routeIs('finance.expenditures.*') || request()->routeIs('finance.loans.*') || request()->routeIs('finance.investors.*') || request()->routeIs('finance.depreciations.*') || request()->routeIs('finance.taxes.*');
    $isSystemActive = request()->routeIs('finance.reports') || request()->routeIs('finance.income.index') || request()->routeIs('finance.balance.index') || request()->routeIs('finance.payments.*') || request()->routeIs('finance.chat.*');
    $isPurchasesActive = request()->routeIs('finance.purchases.*');
    $isOverdueActive = request()->routeIs('finance.overdue.*');
@endphp


    <button class="toggle-sidebar d-md-none">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div>
            <h3><i class="bi bi-cash-coin text-warning me-2"></i> Redvers Finance</h3>
            <nav class="nav flex-column">
                <a href="{{ route('finance.dashboard') }}" class="nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="accordion" id="accordionFinance">
                    <!-- Assets -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $isAssetsActive ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAssets" aria-expanded="{{ $isAssetsActive ? 'true' : 'false' }}">
                                <i class="bi bi-box-seam me-2"></i> Assets & Inventory
                            </button>
                        </h2>
                        <div id="collapseAssets" class="accordion-collapse collapse {{ $isAssetsActive ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('finance.product_categories.index') }}" class="nav-link {{ request()->routeIs('finance.product_categories.*') ? 'active' : '' }}">
                                    <i class="bi bi-tags"></i> Product Categories
                                </a>
                                <a href="{{ route('finance.products.index') }}" class="nav-link {{ request()->routeIs('finance.products.*') ? 'active' : '' }}">
                                    <i class="bi bi-box2"></i> Products
                                </a>
                                <a href="{{ route('finance.cogs.index') }}" class="nav-link {{ request()->routeIs('finance.cogs.*') ? 'active' : '' }}">
                                    <i class="bi bi-boxes"></i> Product Costs (COGS)
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Financials -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $isFinancialsActive ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinance" aria-expanded="{{ $isFinancialsActive ? 'true' : 'false' }}">
                                <i class="bi bi-coin me-2"></i> Financials
                            </button>
                        </h2>
                        <div id="collapseFinance" class="accordion-collapse collapse {{ $isFinancialsActive ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('finance.revenues.index') }}" class="nav-link {{ request()->routeIs('finance.revenues.*') ? 'active' : '' }}">
                                    <i class="bi bi-cash-coin"></i> Revenue Streams
                                </a>
                                <a href="{{ route('finance.expenditures.index') }}" class="nav-link {{ request()->routeIs('finance.expenditures.*') ? 'active' : '' }}">
                                    <i class="bi bi-wallet2"></i> Operating Expenses
                                </a>
                                <a href="{{ route('finance.loans.index') }}" class="nav-link {{ request()->routeIs('finance.loans.*') ? 'active' : '' }}">
                                    <i class="bi bi-bank"></i> Loans & Credits
                                </a>
                                <a href="{{ route('finance.investors.index') }}" class="nav-link {{ request()->routeIs('finance.investors.*') ? 'active' : '' }}">
                                    <i class="bi bi-person-lines-fill"></i> Investors
                                </a>
                                <a href="{{ route('finance.depreciations.index') }}" class="nav-link {{ request()->routeIs('finance.depreciations.*') ? 'active' : '' }}">
                                    <i class="bi bi-graph-down-arrow"></i> Asset Depreciation
                                </a>
                                <a href="{{ route('finance.taxes.index') }}" class="nav-link {{ request()->routeIs('finance.taxes.*') ? 'active' : '' }}">
                                    <i class="bi bi-calculator"></i> Tax Settings
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Bike Purchases -->
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button {{ $isPurchasesActive ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePurchases" aria-expanded="{{ $isPurchasesActive ? 'true' : 'false' }}">
            <i class="bi bi-truck-front me-2"></i> Bike Purchases
        </button>
    </h2>
    <div id="collapsePurchases" class="accordion-collapse collapse {{ $isPurchasesActive ? 'show' : '' }}">
        <div class="accordion-body">
            <a href="{{ route('finance.purchases.index') }}" class="nav-link {{ request()->routeIs('finance.purchases.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> View Purchases
            </a>
        </div>
    </div>
</div>

<!-- Overdue Payments -->
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button {{ $isOverdueActive ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOverdue" aria-expanded="{{ $isOverdueActive ? 'true' : 'false' }}">
            <i class="bi bi-clock-history me-2"></i> Overdue Payments
        </button>
    </h2>
    <div id="collapseOverdue" class="accordion-collapse collapse {{ $isOverdueActive ? 'show' : '' }}">
        <div class="accordion-body">
            <a href="{{ route('finance.overdue.index') }}" class="nav-link {{ request()->routeIs('finance.overdue.*') ? 'active' : '' }}">
                <i class="bi bi-clock"></i> All Overdue Records
            </a>
        </div>
    </div>
</div>



                    <!-- System -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $isSystemActive ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSystem" aria-expanded="{{ $isSystemActive ? 'true' : 'false' }}">
                                <i class="bi bi-gear me-2"></i> System
                            </button>
                        </h2>
                        <div id="collapseSystem" class="accordion-collapse collapse {{ $isSystemActive ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('finance.reports') }}" class="nav-link {{ request()->is('finance/reports') ? 'active' : '' }}">
                                    <i class="bi bi-file-earmark-text"></i> Reports
                                </a>
                                <a href="{{ route('finance.income.index') }}" class="nav-link {{ request()->routeIs('finance.income.index') ? 'active fw-bold text-primary' : '' }}">
                                    <i class="bi bi-graph-up me-2"></i> Income Statement
                                </a>
                                <a href="{{ route('finance.balance.index') }}" class="nav-link {{ request()->routeIs('finance.balance.index') ? 'active fw-bold text-primary' : '' }}">
                                    <i class="bi bi-diagram-3 me-2"></i> Balance Sheet
                                </a>
                                <a href="{{ route('finance.payments.index') }}" class="nav-link {{ request()->routeIs('finance.payments.*') ? 'active' : '' }}">
                                    <i class="bi bi-credit-card-2-front"></i> Payments
                                </a>
                                <a href="{{ route('finance.chat.users') }}" class="nav-link {{ request()->routeIs('finance.chat.*') ? 'active' : '' }}">
                                    <i class="bi bi-chat-dots"></i> Chat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

    <main class="content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('.toggle-sidebar').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>

    @stack('scripts')
</body>
</html>
