<!-- resources/views/auth/agent-login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Login - Redvers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #064e3b, #0f766e);
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
            border: 4px solid #0d9488;
            animation: pulse 2s infinite ease-in-out;
        }

        .login-title {
            font-weight: 700;
            color: #064e3b;
            margin-top: 1rem;
        }

        .btn-teal {
            background-color: #0d9488;
            border: none;
        }

        .btn-teal:hover {
            background-color: #0f766e;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        /* 🌊 Animated Orb Background */
        .agent-orb {
            position: absolute;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, #2dd4bf, #14b8a6, #0d9488);
            border-radius: 50% 45% 60% 50% / 50% 60% 40% 55%;
            filter: blur(100px);
            opacity: 0.5;
            z-index: 0;
            animation: moveOrb 22s ease-in-out infinite, morphShape 14s ease-in-out infinite, shiftColors 11s ease-in-out infinite;
        }

        @keyframes moveOrb {
            0%   { transform: translate(5vw, 70vh); }
            20%  { transform: translate(60vw, 40vh); }
            40%  { transform: translate(30vw, 20vh); }
            60%  { transform: translate(70vw, 80vh); }
            80%  { transform: translate(20vw, 50vh); }
            100% { transform: translate(5vw, 70vh); }
        }

        @keyframes morphShape {
            0%, 100% {
                border-radius: 50% 45% 60% 50% / 50% 60% 40% 55%;
            }
            50% {
                border-radius: 40% 60% 45% 65% / 60% 40% 65% 35%;
            }
        }

        @keyframes shiftColors {
            0%   { background: radial-gradient(circle, #2dd4bf, #14b8a6, #0d9488); }
            33%  { background: radial-gradient(circle, #0d9488, #0f766e, #06b6d4); }
            66%  { background: radial-gradient(circle, #22d3ee, #38bdf8, #0ea5e9); }
            100% { background: radial-gradient(circle, #2dd4bf, #14b8a6, #0d9488); }
        }
    </style>
</head>
<body>

<!-- 🌐 Animated Orb -->
<div class="agent-orb"></div>

<div class="d-flex align-items-center justify-content-center vh-100 px-3">
    <div class="login-container">
        <div class="text-center mb-4">
            <img src="{{ asset('images/redvers.jpeg') }}" alt="Redvers Logo" class="login-logo mb-2">
            <h4 class="login-title">Agent Login</h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('agent.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="agent@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-teal text-white fw-bold">
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
