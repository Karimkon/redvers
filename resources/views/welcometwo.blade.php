<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Redvers Battery Swap System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Meta SEO & OG Tags -->
    <meta name="description" content="Redvers Smart Battery Swap System – seamless electric motorcycle battery management, rider payments, and agent tracking.">
    <meta name="keywords" content="battery swap, electric motorcycles, Uganda, Redvers, EV infrastructure">
    <meta property="og:title" content="Redvers Battery Swap System">
    <meta property="og:description" content="Revolutionizing electric motorcycle infrastructure in Uganda.">
    <meta property="og:image" content="{{ asset('images/redvers.jpeg') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'gradient': 'gradient 8s ease infinite',
                        'electric-pulse': 'electricPulse 4s linear infinite',
                        'flicker': 'flicker 8s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        gradient: {
                            '0%, 100%': { 'background-position': '0% 50%' },
                            '50%': { 'background-position': '100% 50%' },
                        },
                        electricPulse: {
                            '0%': { 'box-shadow': '0 0 0 0 rgba(59, 130, 246, 0.7)' },
                            '70%': { 'box-shadow': '0 0 0 15px rgba(59, 130, 246, 0)' },
                            '100%': { 'box-shadow': '0 0 0 0 rgba(59, 130, 246, 0)' },
                        },
                        flicker: {
                            '0%, 2%, 4%, 54%, 56%, 58%, 100%': { opacity: 1 },
                            '1%, 3%, 55%, 57%': { opacity: 0.6 },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --electric-blue: rgba(59, 130, 246, 0.8);
            --electric-green: rgba(52, 211, 153, 0.8);
            --electric-purple: rgba(168, 85, 247, 0.8);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #0f172a, #1e293b, #0f172a);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: white;
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
        
        .glow-text {
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.7);
        }
        
        .electric-border {
            position: relative;
            overflow: hidden;
        }
        
        .electric-border::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, 
                var(--electric-blue), 
                var(--electric-green), 
                var(--electric-purple), 
                var(--electric-blue));
            background-size: 400% 400%;
            z-index: -1;
            animation: gradient 8s ease infinite;
            border-radius: inherit;
        }
        
        .login-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }
        
        .login-card:hover::before {
            left: 100%;
        }
        
        .stat-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .map-container {
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
        }
        
        .map-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(59, 130, 246, 0.1) 0%,
                rgba(59, 130, 246, 0) 20%,
                rgba(59, 130, 246, 0) 80%,
                rgba(52, 211, 153, 0.1) 100%
            );
            pointer-events: none;
            z-index: 1;
        }
        
        .battery-icon {
            position: relative;
            width: 80px;
            height: 120px;
            border: 4px solid rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }
        
        .battery-icon::before {
            content: '';
            position: absolute;
            top: -10px;
            width: 20px;
            height: 10px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 5px 5px 0 0;
        }
        
        .battery-level {
            position: absolute;
            bottom: 5px;
            width: 70px;
            background: linear-gradient(to top, #3b82f6, #10b981);
            border-radius: 5px;
            animation: batteryCharge 4s infinite alternate;
        }
        
        @keyframes batteryCharge {
            0% { height: 20%; }
            100% { height: 90%; }
        }
        
        .pulse-dot {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #3b82f6;
            border-radius: 50%;
            animation: electric-pulse 2s infinite;
        }
        
        @media (max-width: 640px) {
            .battery-icon {
                width: 60px;
                height: 90px;
            }
            
            .battery-level {
                width: 50px;
            }
        }
    </style>
</head>
<body class="overflow-x-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="absolute top-20 left-10 w-4 h-4 rounded-full bg-blue-500 opacity-20 animate-float"></div>
        <div class="absolute top-1/3 right-20 w-6 h-6 rounded-full bg-purple-500 opacity-20 animate-float animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/4 w-3 h-3 rounded-full bg-emerald-500 opacity-20 animate-float animation-delay-3000"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10 relative">
        <!-- Hero Section -->
        <div class="text-center max-w-4xl fade-in relative z-10">
            <!-- Animated Battery Logo -->
            <div class="mx-auto mb-8 flex justify-center">
                <div class="battery-icon animate-pulse-slow">
                    <div class="battery-level"></div>
                    <div class="pulse-dot top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                </div>
            </div>

            <h1 class="text-4xl sm:text-6xl font-extrabold mb-6 leading-tight tracking-tight glow-text animate-flicker">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-emerald-400 to-purple-500">
                    Redvers Smart Battery Swap
                </span>
            </h1>

            <p class="text-xl text-white/80 mb-10 leading-relaxed max-w-3xl mx-auto">
                Revolutionizing electric mobility with <span class="font-semibold text-blue-300">instant battery swaps</span>, 
                <span class="font-semibold text-emerald-300">smart agent coordination</span>, and 
                <span class="font-semibold text-purple-300">seamless payments</span>.
            </p>

            <!-- Login Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto mb-12">
                <a href="/rider/login" class="login-card electric-border p-4 rounded-xl bg-gray-800/80 backdrop-blur-sm">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-full bg-blue-500/20 text-blue-400 text-xl">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <span class="font-bold">Rider</span>
                        <span class="text-xs text-gray-400 mt-1">Battery Access</span>
                    </div>
                </a>
                
                <a href="/agent/login" class="login-card electric-border p-4 rounded-xl bg-gray-800/80 backdrop-blur-sm">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400 text-xl">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <span class="font-bold">Agent</span>
                        <span class="text-xs text-gray-400 mt-1">Station Management</span>
                    </div>
                </a>
                
                <a href="/admin/login" class="login-card electric-border p-4 rounded-xl bg-gray-800/80 backdrop-blur-sm">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-full bg-purple-500/20 text-purple-400 text-xl">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <span class="font-bold">Admin</span>
                        <span class="text-xs text-gray-400 mt-1">System Control</span>
                    </div>
                </a>
                
                <a href="/finance/login" class="login-card electric-border p-4 rounded-xl bg-gray-800/80 backdrop-blur-sm">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-full bg-amber-500/20 text-amber-400 text-xl">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="font-bold">Finance</span>
                        <span class="text-xs text-gray-400 mt-1">Revenue Analytics</span>
                    </div>
                </a>
            </div>

            <!-- Website Link Section -->
            <div class="mt-6 text-sm text-gray-300">
                <a href="https://redversemobility.com" target="_blank" class="inline-flex items-center gap-2 text-blue-400 hover:text-white transition duration-300 group">
                    <span class="group-hover:underline">Explore Our EV Ecosystem</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-20 max-w-6xl w-full fade-in px-4">
            <h2 class="text-center text-3xl font-bold mb-12 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
                Powering Uganda's EV Revolution
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="stat-card p-6 rounded-xl relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-blue-500/10 blur-xl"></div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-500/20 text-blue-400 text-xl">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-blue-400 mb-1" id="swapCounter">0</div>
                            <h3 class="text-lg font-semibold text-white">Battery Swaps</h3>
                            <p class="text-sm text-gray-400 mt-1">Completed across Uganda</p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card p-6 rounded-xl relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-emerald-500/10 blur-xl"></div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400 text-xl">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-emerald-400 mb-1"><span id="uptimeCounter">0</span>%</div>
                            <h3 class="text-lg font-semibold text-white">System Uptime</h3>
                            <p class="text-sm text-gray-400 mt-1">Real-time availability</p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card p-6 rounded-xl relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-purple-500/10 blur-xl"></div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-purple-500/20 text-purple-400 text-xl">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-purple-400 mb-1">+<span id="agentCounter">0</span></div>
                            <h3 class="text-lg font-semibold text-white">Active Agents</h3>
                            <p class="text-sm text-gray-400 mt-1">Across 5 major districts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="w-full mt-20 fade-in px-4">
            <h2 class="text-center text-3xl font-bold mb-12 bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-blue-400">
                Our Network Coverage
            </h2>
            <div class="max-w-6xl mx-auto w-full h-[500px] map-container relative">
                <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                    <div class="pulse-dot"></div>
                    <div class="absolute w-4 h-4 bg-white rounded-full animate-ping opacity-75"></div>
                </div>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127644.47038975001!2d32.521477035625325!3d0.3136116922570256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbb6d63e802d1%3A0x5c6f4e987ae3031f!2sKampala!5e0!3m2!1sen!2sug!4v1706183537534!5m2!1sen!2sug"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                
                <!-- Station Finder UI -->
                <div class="absolute bottom-4 left-4 right-4 bg-gray-900/80 backdrop-blur-sm rounded-lg p-4 shadow-lg z-10">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-300 mb-1">Find Nearest Station</label>
                            <div class="relative">
                                <input type="text" placeholder="Enter your location" class="w-full bg-gray-800 border border-gray-700 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button class="absolute right-2 top-2 text-gray-400 hover:text-white">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition flex items-center justify-center gap-2 sm:w-auto">
                            <i class="fas fa-location-arrow"></i>
                            <span>Locate</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-20 text-center fade-in w-full max-w-6xl px-4">
            <div class="flex flex-col md:flex-row justify-between items-center py-8 border-t border-gray-800">
                <div class="mb-4 md:mb-0">
                    <div class="text-xl font-bold text-white mb-2">Redvers Technologies</div>
                    <p class="text-sm text-gray-400">Powering the future of electric mobility in Africa</p>
                </div>
                
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fab fa-youtube text-xl"></i>
                    </a>
                </div>
            </div>
            
            <div class="text-sm text-gray-500 py-4 border-t border-gray-800">
                © {{ date('Y') }} Redvers Technologies. All rights reserved.
            </div>
        </footer>
    </div>

    <!-- Animation Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Fade-in animations
            document.querySelectorAll('.fade-in').forEach((el, i) => {
                setTimeout(() => el.classList.add('visible'), i * 200);
            });
            
            // Animated counters
            function animateCounter(elementId, target, duration = 2000) {
                const element = document.getElementById(elementId);
                const start = 0;
                const increment = target / (duration / 16);
                let current = start;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        clearInterval(timer);
                        current = target;
                    }
                    element.textContent = Math.floor(current).toLocaleString();
                }, 16);
            }
            
            // Start counters when stats section is visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter('swapCounter', 3200);
                        animateCounter('uptimeCounter', 99.8);
                        animateCounter('agentCounter', 50);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            const statsSection = document.querySelector('.fade-in:nth-of-type(2)');
            if (statsSection) observer.observe(statsSection);
        });
    </script>
</body>
</html>