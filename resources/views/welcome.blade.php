<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Redvers Battery Swap System</title>

    <!-- Tailwind Setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>

    <link rel="icon" href="{{ asset('images/fevicon.png') }}" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
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

        .pulse-ring {
            position: relative;
        }

        .pulse-ring::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
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
            color: #facc15;
            animation: flicker 1.5s infinite;
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; text-shadow: 0 0 8px #facc15; }
            50% { opacity: 0.7; text-shadow: 0 0 4px #facc15; }
        }

        .theme-toggle-icon {
            animation: bounce-shake 3s infinite ease-in-out;
        }

        @keyframes bounce-shake {
            0%, 100% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(10deg) scale(1.1); }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-black via-gray-900 to-gray-800 text-white transition-all duration-300 dark:from-white dark:to-gray-100 dark:text-black">

<!-- 🌙 Theme Icon -->
<div class="fixed top-4 right-4 z-50">
    <button onclick="toggleTheme()" aria-label="Toggle Theme">
        <svg id="theme-icon" xmlns="http://www.w3.org/2000/svg" class="theme-toggle-icon w-7 h-7 text-white dark:text-black hover:scale-125 transition-all duration-300"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path id="icon-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m8.66-13.66l-.707.707M4.05 19.95l-.707.707M21 12h-1M4 12H3m16.95 7.95l-.707-.707M4.05 4.05l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>
</div>

<div class="min-h-screen w-full overflow-x-hidden flex flex-col items-center justify-center px-4 sm:px-8 py-12">
    <div class="text-center max-w-3xl w-full">
        <div class="pulse-ring mb-5">
            <img src="{{ asset('images/fevicon.png') }}" alt="Redvers Logo"
                 class="mx-auto w-20 sm:w-24 h-20 sm:h-24 rounded-full border-4 border-cyan-400 shadow-xl object-contain" />
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold leading-tight drop-shadow-lg mb-3">
            <span class="electric-icon">⚡</span> Redvers Hub
        </h1>

        <p class="text-sm sm:text-lg text-white/70 dark:text-gray-800 mb-8 px-2">
            Revolutionizing electric motorcycle infrastructure with real-time battery swaps, intelligent payment tracking, and efficient agent support.
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 text-sm sm:text-base">
            <a href="/rider/login" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 sm:px-5 sm:py-3 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">Rider</a>
            <a href="/agent/login" class="bg-emerald-600 hover:bg-emerald-700 px-3 py-2 sm:px-5 sm:py-3 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">Agent</a>
            <a href="/admin/login" class="bg-purple-600 hover:bg-purple-700 px-3 py-2 sm:px-5 sm:py-3 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">Admin</a>
            <a href="/finance/login" class="bg-gray-700 hover:bg-gray-600 px-3 py-2 sm:px-5 sm:py-3 rounded-xl shadow-lg text-center font-semibold transition-all duration-300">Finance</a>
        </div>

        <div class="mt-6 text-xs sm:text-sm text-gray-300 dark:text-gray-700">
            <a href="https://redversemobility.com" target="_blank" class="inline-flex items-center gap-2 text-cyan-400 hover:text-white dark:text-blue-600 dark:hover:text-black transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                Visit Our Main Website
            </a>
        </div>
    </div>

    <!-- 📊 Impact Stats -->
    <div class="mt-16 sm:mt-20 w-full max-w-4xl text-center fade-in px-4">
        <h2 class="text-xl sm:text-2xl font-semibold mb-6">🚀 Platform Impact</h2>

        <div id="impactLoader" class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-pulse">
            <div class="bg-gray-700 h-24 rounded-xl"></div>
            <div class="bg-gray-700 h-24 rounded-xl"></div>
            <div class="bg-gray-700 h-24 rounded-xl"></div>
        </div>

        <div id="impactStats" class="grid grid-cols-1 sm:grid-cols-3 gap-4 hidden">
            <div class="bg-gray-800 dark:bg-white text-white dark:text-black p-4 sm:p-6 rounded-xl shadow-xl hover:scale-105 transition">
                <h3 class="text-xl font-bold">+3,200</h3>
                <p class="text-xs sm:text-sm">Battery Swaps Completed</p>
            </div>
            <div class="bg-gray-800 dark:bg-white text-white dark:text-black p-4 sm:p-6 rounded-xl shadow-xl hover:scale-105 transition">
                <h3 class="text-xl font-bold">99.8%</h3>
                <p class="text-xs sm:text-sm">Station Uptime</p>
            </div>
            <div class="bg-gray-800 dark:bg-white text-white dark:text-black p-4 sm:p-6 rounded-xl shadow-xl hover:scale-105 transition">
                <h3 class="text-xl font-bold">50+ Agents</h3>
                <p class="text-xs sm:text-sm">Serving across 5 districts</p>
            </div>
        </div>
    </div>

    <!-- 📍 Map -->
    <div class="mt-16 sm:mt-20 w-full px-4 fade-in">
        <h2 class="text-xl sm:text-2xl text-center font-semibold mb-6">📍 Our Presence in Uganda</h2>
        <div class="w-full h-64 sm:h-96 border-4 border-white rounded-xl overflow-hidden shadow-2xl transition duration-1000 ease-in-out scale-95 hover:scale-100">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127644.47038975001!2d32.521477035625325!3d0.3136116922570256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbb6d63e802d1%3A0x5c6f4e987ae3031f!2sKampala!5e0!3m2!1sen!2sug!4v1706183537534!5m2!1sen!2sug"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-16 sm:mt-20 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-700 fade-in px-4">
        © {{ date('Y') }} Redvers Technologies. All rights reserved.
    </footer>
</div>

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.fade-in').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), i * 200);
        });

        setTimeout(() => {
            document.getElementById("impactLoader").style.display = "none";
            document.getElementById("impactStats").classList.remove("hidden");
        }, 1500);

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    });

    function toggleTheme() {
        const root = document.documentElement;
        root.classList.toggle('dark');
        localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
    }
</script>
</body>
</html>
