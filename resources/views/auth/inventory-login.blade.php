<!-- resources/views/auth/inventory-login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Login - Redvers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e40af, #0ea5e9);
            color: #fff;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .login-container {
            max-width: 420px;
            margin: auto;
            padding: 2.5rem;
            border-radius: 16px;
            background-color: #ffffff;
            color: #333;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 2;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #1e3a8a;
            animation: pulse 2s infinite ease-in-out;
        }

        .login-title {
            font-weight: 700;
            color: #1e3a8a;
            margin-top: 1rem;
        }

        .btn-blue {
            background-color: #1e3a8a;
            border: none;
        }

        .btn-blue:hover {
            background-color: #1e40af;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        .inventory-orb {
            position: absolute;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, #60a5fa, #3b82f6, #1e40af);
            border-radius: 50% 60% 55% 45% / 45% 55% 60% 50%;
            filter: blur(90px);
            opacity: 0.5;
            z-index: 0;
            animation: moveOrb 20s ease-in-out infinite, morphShape 12s ease-in-out infinite;
        }

        @keyframes moveOrb {
            0%   { transform: translate(10vw, 80vh); }
            50%  { transform: translate(60vw, 30vh); }
            100% { transform: translate(10vw, 80vh); }
        }

        @keyframes morphShape {
            0%, 100% { border-radius: 50% 60% 55% 45% / 45% 55% 60% 50%; }
            50% { border-radius: 40% 50% 60% 60% / 50% 60% 50% 40%; }
        }
    </style>
</head>
<body>

<!-- 🌐 Animated Orb -->
<div class="inventory-orb"></div>

<div class="d-flex align-items-center justify-content-center vh-100 px-3">
    <div class="login-container">
        <div class="text-center mb-4">
            <img src="{{ asset('images/redvers.jpeg') }}" alt="Redvers Logo" class="login-logo mb-2">
            <h4 class="login-title">Inventory Login</h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('inventory.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="inventory@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-blue text-white fw-bold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="https://redversemobility.com" target="_blank" class="text-decoration-none small text-muted d-inline-flex align-items-center">
                <i class="bi bi-globe me-1"></i> Visit Redvers Website
            </a>
        </div>

        <div class="text-center mt-2">
            <small class="text-muted">© {{ date('Y') }} Redvers Technologies. All rights reserved.</small>
        </div>
    </div>
</div>

</body>
</html>
