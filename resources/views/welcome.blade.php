<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Redvers Battery Swap System</title>

    <!-- SEO Meta -->
    <meta name="description" content="Redvers Smart Battery Swap System – seamless electric motorcycle battery management, rider payments, and agent tracking.">
    <meta name="keywords" content="battery swap, electric motorcycles, Uganda, Redvers, EV infrastructure">
    <meta property="og:title" content="Redvers Battery Swap System">
    <meta property="og:description" content="Revolutionizing electric motorcycle infrastructure in Uganda.">
    <meta property="og:image" content="{{ asset('images/redvers.jpeg') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0f0f, #1f1f2e);
        }
        .fade-in {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 1s ease, transform 1s ease;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .glow-tile:hover {
            box-shadow: 0 0 15px 5px rgba(59, 130, 246, 0.6);
        }
        .pulse-ring {
            position: relative;
        }
        .pulse-ring::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120%;
            height: 120%;
            border-radius: 9999px;
            background-color: rgba(255, 255, 255, 0.1);
            animation: pulse 2.5s infinite;
        }
        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
            100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
        }

        .electric-icon {
    display: inline-block;
    position: relative;
    color: #facc15; /* bright yellow */
    animation: flicker 1.5s infinite;
}

.electric-icon::before,
.electric-icon::after {
    content: "";
    position: absolute;
    width: 8px;
    height: 8px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    animation: spark 0.8s infinite ease-in-out;
    opacity: 0;
}

.electric-icon::before {
    top: -10px;
    left: -6px;
}

.electric-icon::after {
    bottom: -10px;
    right: -6px;
}

@keyframes spark {
    0%, 100% { opacity: 0; transform: scale(0.5); }
    50% { opacity: 1; transform: scale(1.4); }
}

@keyframes flicker {
    0%, 100% { opacity: 1; text-shadow: 0 0 8px #facc15, 0 0 12px #facc15; }
    50% { opacity: 0.7; text-shadow: 0 0 4px #facc15, 0 0 8px #facc15; }
}

    </style>
</head>
<body class="text-white">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
        <div class="text-center max-w-2xl fade-in">
            <div class="pulse-ring mb-6">
                <img src="{{ asset('images/redvers.jpeg') }}" alt="Redvers Logo" class="mx-auto w-24 h-24 rounded-full border-4 border-cyan-400 shadow-xl" />
            </div>
            <h1 class="text-5xl font-extrabold leading-tight drop-shadow-lg"><span class="electric-icon">⚡</span> Redvers E-Mobility Suite System</h1>
            <p class="text-lg text-white/70 mt-4 mb-8">Revolutionizing electric motorcycle infrastructure with real-time battery swaps, intelligent payment tracking, and efficient agent support.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <a href="/rider/login" class="glow-tile bg-blue-600 hover:bg-blue-700 px-6 py-4 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">
                    <i class="bi bi-person"></i> Rider Login
                </a>
                <a href="/agent/login" class="glow-tile bg-emerald-600 hover:bg-emerald-700 px-6 py-4 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">
                    <i class="bi bi-geo"></i> Agent Login
                </a>
                <a href="/admin/login" class="glow-tile bg-purple-600 hover:bg-purple-700 px-6 py-4 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">
                    <i class="bi bi-shield-lock"></i> Admin Login
                </a>
                <a href="/finance/login" class="glow-tile bg-gray-700 hover:bg-gray-600 px-6 py-4 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">
                    <i class="bi bi-cash-stack"></i> Finance Login
                </a>
            </div>

            <div class="mt-6 text-sm text-gray-300">
                <a href="https://redversemobility.com" target="_blank" class="inline-flex items-center gap-2 text-cyan-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    Visit Our Main Website
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-20 w-full max-w-4xl text-center fade-in">
            <h2 class="text-2xl font-semibold mb-6">🚀 Platform Impact</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-gray-800 p-6 rounded-xl shadow-xl hover:scale-105 transition text-white">
                    <h3 class="text-2xl font-bold">+3,200</h3>
                    <p class="text-sm">Battery Swaps Completed</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-xl shadow-xl hover:scale-105 transition text-white">
                    <h3 class="text-2xl font-bold">99.8%</h3>
                    <p class="text-sm">Station Uptime</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-xl shadow-xl hover:scale-105 transition text-white">
                    <h3 class="text-2xl font-bold">50+ Agents</h3>
                    <p class="text-sm">Serving across 5 districts</p>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="mt-20 w-full fade-in">
            <h2 class="text-2xl text-center font-semibold mb-6">📍 Our Presence in Uganda</h2>
            <div class="max-w-5xl mx-auto w-full h-96 border-4 border-white rounded-xl overflow-hidden shadow-2xl">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127644.47038975001!2d32.521477035625325!3d0.3136116922570256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbb6d63e802d1%3A0x5c6f4e987ae3031f!2sKampala!5e0!3m2!1sen!2sug!4v1706183537534!5m2!1sen!2sug"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-20 text-center text-sm text-gray-500 fade-in">
            © {{ date('Y') }} Redvers Technologies. All rights reserved.
        </footer>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.fade-in').forEach((el, i) => {
                setTimeout(() => el.classList.add('visible'), i * 200);
            });
        });
    </script>
</body>
</html>


