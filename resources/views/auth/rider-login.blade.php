<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rider Login - Redvers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1b4b, #6d28d9);
            color: #fff;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            margin: auto;
            padding: 2.5rem;
            border-radius: 16px;
            background-color: #ffffff;
            color: #333;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 2;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            font-size: 2.8rem;
            color: #6d28d9;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 4px solid #9333ea;
            background-color: #f3f0ff;
            margin: 0 auto;
            animation: pulse 2s infinite;
        }

        .login-title {
            font-weight: 700;
            color: #1e1b4b;
            margin-top: 1rem;
        }

        .btn-purple {
            background-color: #6d28d9;
            border: none;
        }

        .btn-purple:hover {
            background-color: #5b21b6;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        /* 🪐 Glowing Abstract Orb */
        .animated-orb {
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, #ff0080, #7928ca, #2afadf);
            border-radius: 50% 45% 55% 50% / 55% 50% 45% 50%;
            filter: blur(80px);
            opacity: 0.6;
            animation: moveOrb 20s ease-in-out infinite, rainbowShift 15s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes moveOrb {
            0%   { transform: translate(10vw, 60vh) scale(1); }
            25%  { transform: translate(60vw, 40vh) scale(1.1); }
            50%  { transform: translate(30vw, 10vh) scale(0.95); }
            75%  { transform: translate(70vw, 70vh) scale(1.05); }
            100% { transform: translate(10vw, 60vh) scale(1); }
        }

        @keyframes rainbowShift {
            0%   { background: radial-gradient(circle, #ff0080, #7928ca, #2afadf); }
            25%  { background: radial-gradient(circle, #2afadf, #00ff99, #18ffff); }
            50%  { background: radial-gradient(circle, #ff8c00, #ff0080, #ff0000); }
            75%  { background: radial-gradient(circle, #00ffff, #0099ff, #6600ff); }
            100% { background: radial-gradient(circle, #ff0080, #7928ca, #2afadf); }
        }
    </style>
</head>
<body>

<!-- 🎨 Abstract Glowing Orb -->
<div class="animated-orb"></div>

<!-- 🔐 Login Form -->
<div class="d-flex align-items-center justify-content-center vh-100 px-3">
    <div class="login-container">
        <div class="text-center mb-4">
            <div class="login-icon">
                <i class="bi bi-battery-charging"></i>
            </div>
            <h4 class="login-title">Rider Login</h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('rider.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="rider@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-purple text-white fw-bold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="https://redversemobility.com" target="_blank" class="text-decoration-none small text-muted d-inline-flex align-items-center">
                <i class="bi bi-globe me-1"></i> Visit Our Website
            </a>
        </div>

        <div class="text-center mt-3">
            <small class="text-muted">© {{ date('Y') }} Redvers Technologies. All rights reserved.</small>
        </div>
    </div>
</div>

</body>
</html>
