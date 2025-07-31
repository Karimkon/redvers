<!-- Mechanic Login Blade File -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mechanic Login - Redvers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a, #334155);
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

        .login-icon {
            width: 80px;
            height: 80px;
            font-size: 3rem;
            color: #0d9488;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 4px solid #0d9488;
            background-color: #d1fae5;
            margin: 0 auto;
            animation: pulse 2s infinite ease-in-out;
        }

        .login-title {
            font-weight: 700;
            color: #0f172a;
            margin-top: 1rem;
        }

        .btn-blue {
            background-color: #0d9488;
            border: none;
        }

        .btn-blue:hover {
            background-color: #0f766e;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        .mechanic-orb {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #5eead4, #14b8a6, #0d9488);
            border-radius: 50% 45% 60% 50% / 50% 60% 40% 55%;
            filter: blur(100px);
            opacity: 0.4;
            animation: moveOrb 20s ease-in-out infinite, morphShape 10s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes moveOrb {
            0%   { transform: translate(10vw, 80vh); }
            25%  { transform: translate(60vw, 20vh); }
            50%  { transform: translate(30vw, 50vh); }
            75%  { transform: translate(70vw, 10vh); }
            100% { transform: translate(10vw, 80vh); }
        }

        @keyframes morphShape {
            0%, 100% {
                border-radius: 50% 45% 60% 50% / 50% 60% 40% 55%;
            }
            50% {
                border-radius: 40% 60% 45% 65% / 60% 40% 65% 35%;
            }
        }
    </style>
</head>
<body>

<div class="mechanic-orb"></div>

<div class="d-flex align-items-center justify-content-center vh-100 px-3">
    <div class="login-container">
        <div class="text-center mb-4">
            <div class="login-icon">
                <i class="bi bi-tools"></i>
            </div>
            <h4 class="login-title">Mechanic Login</h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('mechanic.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="mechanic@redvers.com">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-blue fw-bold text-white">
                    <i class="bi bi-gear-fill me-1"></i> Login
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="https://redversemobility.com" target="_blank" class="text-decoration-none small text-muted d-inline-flex align-items-center">
                <i class="bi bi-globe me-1"></i> Visit Redvers Website
            </a>
        </div>

        <div class="text-center mt-2">
            <small class="text-muted">© {{ date('Y') }} Redvers Mechanics. All rights reserved.</small>
        </div>
    </div>
</div>

</body>
</html>
