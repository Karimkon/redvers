<!-- resources/views/admin/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Redvers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
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
            border: 3px solid #0d6efd;
            animation: pulse 2s infinite ease-in-out;
        }

        .login-title {
            font-weight: 700;
            color: #0f172a;
            margin-top: 1rem;
        }

        .btn-blue {
            background-color: #0d6efd;
            border: none;
        }

        .btn-blue:hover {
            background-color: #0b5ed7;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        /* 🪐 Abstract Glowing Orb */
        .animated-orb {
            position: absolute;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, #0d6efd, #6610f2, #e83e8c);
            border-radius: 50% 45% 55% 50% / 55% 50% 45% 50%;
            filter: blur(100px);
            opacity: 0.5;
            z-index: 0;
            animation: floatOrb 18s ease-in-out infinite, shiftColors 10s ease-in-out infinite;
        }

        @keyframes floatOrb {
            0%   { transform: translate(20vw, 60vh) scale(1); }
            25%  { transform: translate(60vw, 30vh) scale(1.1); }
            50%  { transform: translate(40vw, 10vh) scale(0.95); }
            75%  { transform: translate(70vw, 70vh) scale(1.05); }
            100% { transform: translate(20vw, 60vh) scale(1); }
        }

        @keyframes shiftColors {
            0%   { background: radial-gradient(circle, #0d6efd, #6610f2, #e83e8c); }
            33%  { background: radial-gradient(circle, #6610f2, #20c997, #17a2b8); }
            66%  { background: radial-gradient(circle, #e83e8c, #fd7e14, #ffc107); }
            100% { background: radial-gradient(circle, #0d6efd, #6610f2, #e83e8c); }
        }
    </style>
</head>
<body>

<!-- 🌈 Orb Background -->
<div class="animated-orb"></div>

<!-- 🛡 Admin Login Card -->
<div class="d-flex align-items-center justify-content-center vh-100 px-3">
    <div class="login-container">
        <div class="text-center mb-4">
            <img src="{{ asset('images/redvers.jpeg') }}" alt="Redvers Logo" class="login-logo mb-2">
            <h4 class="login-title">Administrator Login</h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="admin@example.com">
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
                <i class="bi bi-globe me-1"></i> Visit Main Website
            </a>
        </div>

        <div class="text-center mt-2">
            <small class="text-muted">© {{ date('Y') }} Redvers Technologies. All rights reserved.</small>
        </div>
    </div>
</div>

</body>
</html>
