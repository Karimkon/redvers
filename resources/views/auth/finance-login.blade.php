<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Finance Login - Redvers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #052e16, #14532d);
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
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 4px solid #198754;
            background-color: #e9f9ef;
            margin: 0 auto;
            animation: pulse 2s infinite ease-in-out;
        }

        .login-title {
            font-weight: 700;
            color: #052e16;
            margin-top: 1rem;
        }

        .btn-green {
            background-color: #198754;
            border: none;
        }

        .btn-green:hover {
            background-color: #146c43;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        /* 🌿 Morphing Orb Background */
        .finance-orb {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #34d399, #22c55e, #16a34a);
            border-radius: 50% 45% 60% 50% / 50% 60% 40% 55%;
            filter: blur(100px);
            opacity: 0.5;
            animation: moveOrb 25s ease-in-out infinite, morphShape 15s ease-in-out infinite, shiftFinanceColors 12s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes moveOrb {
            0%   { transform: translate(10vw, 70vh); }
            20%  { transform: translate(60vw, 50vh); }
            40%  { transform: translate(30vw, 10vh); }
            60%  { transform: translate(80vw, 30vh); }
            80%  { transform: translate(40vw, 70vh); }
            100% { transform: translate(10vw, 70vh); }
        }

        @keyframes morphShape {
            0%, 100% {
                border-radius: 50% 45% 60% 50% / 50% 60% 40% 55%;
            }
            50% {
                border-radius: 40% 60% 45% 65% / 60% 40% 65% 35%;
            }
        }

        @keyframes shiftFinanceColors {
            0%   { background: radial-gradient(circle, #34d399, #22c55e, #16a34a); }
            33%  { background: radial-gradient(circle, #16a34a, #0f766e, #10b981); }
            66%  { background: radial-gradient(circle, #4ade80, #a3e635, #86efac); }
            100% { background: radial-gradient(circle, #34d399, #22c55e, #16a34a); }
        }
    </style>
</head>
<body>

<!-- 🌈 Glowing Orb Background -->
<div class="finance-orb"></div>

<div class="d-flex align-items-center justify-content-center vh-100 px-3">
    <div class="login-container">
        <div class="text-center mb-4">
            <div class="login-icon">
                <i class="bi bi-currency-exchange"></i>
            </div>
            <h4 class="login-title">Finance Login</h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('finance.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="finance@redvers.com">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-green fw-bold text-white">
                    <i class="bi bi-cash-stack me-1"></i> Login
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="https://redversemobility.com" target="_blank" class="text-decoration-none small text-muted d-inline-flex align-items-center">
                <i class="bi bi-globe me-1"></i> Visit Redvers Website
            </a>
        </div>

        <div class="text-center mt-2">
            <small class="text-muted">© {{ date('Y') }} Redvers Finance. All rights reserved.</small>
        </div>
    </div>
</div>

</body>
</html>
