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
                        sans: ['"Exo 2"', 'Inter', 'sans-serif'],
                    },
                    animation: {
                        'neon-pulse': 'neonPulse 3s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'particle-rotate': 'particleRotate 20s linear infinite',
                        'circuit-flicker': 'circuitFlicker 8s linear infinite',
                        'scan-line': 'scanLine 5s linear infinite',
                        'hologram': 'hologram 8s ease infinite',
                    },
                    keyframes: {
                        neonPulse: {
                            '0%, 100%': { 
                                'text-shadow': '0 0 5px #3b82f6, 0 0 10px #3b82f6, 0 0 15px #3b82f6, 0 0 20px #3b82f6',
                                'opacity': '1'
                            },
                            '50%': { 
                                'text-shadow': '0 0 2px #3b82f6, 0 0 5px #3b82f6',
                                'opacity': '0.8'
                            },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        particleRotate: {
                            '0%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(360deg)' },
                        },
                        circuitFlicker: {
                            '0%, 2%, 4%, 54%, 56%, 58%, 100%': { opacity: 1 },
                            '1%, 3%, 55%, 57%': { opacity: 0.3 },
                        },
                        scanLine: {
                            '0%': { transform: 'translateY(-100%)' },
                            '100%': { transform: 'translateY(100vh)' },
                        },
                        hologram: {
                            '0%, 100%': { 
                                'filter': 'drop-shadow(0 0 5px rgba(59, 130, 246, 0.8))',
                                'opacity': '1'
                            },
                            '50%': { 
                                'filter': 'drop-shadow(0 0 15px rgba(59, 130, 246, 0.5))',
                                'opacity': '0.7'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;800;900&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --electric-blue: rgba(59, 130, 246, 0.8);
            --electric-green: rgba(52, 211, 153, 0.8);
            --electric-purple: rgba(168, 85, 247, 0.8);
        }
        
        body {
            font-family: 'Exo 2', 'Inter', sans-serif;
            background: radial-gradient(circle at center, #0f172a 0%, #020617 100%);
            color: white;
            overflow-x: hidden;
        }
        
        .holographic-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .holographic-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.8), transparent);
            animation: scanLine 5s linear infinite;
        }
        
        .circuit-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.3;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, var(--electric-blue), transparent 70%);
            opacity: 0.6;
            filter: blur(1px);
        }
        
        .battery-animation {
            position: relative;
            width: 100px;
            height: 180px;
            border: 3px solid rgba(59, 130, 246, 0.8);
            border-radius: 15px;
            background: rgba(15, 23, 42, 0.5);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
            overflow: hidden;
        }
        
        .battery-animation::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 8px;
            background: rgba(59, 130, 246, 0.8);
            border-radius: 5px 5px 0 0;
        }
        
        .battery-level {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, #3b82f6, #10b981);
            animation: chargeCycle 4s ease-in-out infinite alternate;
        }
        
        @keyframes chargeCycle {
            0% { height: 10%; opacity: 0.7; }
            100% { height: 95%; opacity: 1; }
        }
        
        .energy-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, transparent 70%);
            animation: pulseExpand 3s ease-out infinite;
        }
        
        @keyframes pulseExpand {
            0% { transform: scale(0.8); opacity: 0.8; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        
        .login-orb {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .login-orb::before {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            padding: 2px;
            background: linear-gradient(45deg, var(--electric-blue), var(--electric-purple));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: rotateBorder 4s linear infinite;
        }
        
        @keyframes rotateBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .stat-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, var(--color) 0%, transparent 70%);
            opacity: 0.1;
            z-index: -1;
        }
        
        .map-marker {
            position: absolute;
            width: 20px;
            height: 20px;
            background: #3b82f6;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            animation: markerPulse 2s ease infinite;
        }
        
        .map-marker::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            background: rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            top: -15px;
            left: -15px;
        }
        
        @keyframes markerPulse {
            0% { transform: rotate(-45deg) scale(1); }
            50% { transform: rotate(-45deg) scale(1.2); }
            100% { transform: rotate(-45deg) scale(1); }
        }
        
        .circuit-node {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #3b82f6;
            border-radius: 50%;
            animation: nodePulse 2s ease infinite alternate;
        }
        
        @keyframes nodePulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
            100% { transform: scale(1.3); box-shadow: 0 0 0 5px rgba(59, 130, 246, 0); }
        }
    </style>
</head>
<body class="relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden -z-50 opacity-30">
        <div class="circuit-overlay"></div>
        <div class="particle top-1/4 left-1/5 w-3 h-3 animate-float animation-delay-1000"></div>
        <div class="particle top-1/3 right-1/4 w-4 h-4 animate-float animation-delay-2000"></div>
        <div class="particle bottom-1/4 left-1/3 w-2 h-2 animate-float animation-delay-3000"></div>
        <div class="absolute top-0 left-0 w-full h-full animate-particle-rotate">
            <div class="circuit-node top-1/5 left-1/4 animation-delay-0"></div>
            <div class="circuit-node top-2/5 right-1/3 animation-delay-500"></div>
            <div class="circuit-node bottom-1/3 left-1/2 animation-delay-1000"></div>
            <div class="circuit-node bottom-1/5 right-1/4 animation-delay-1500"></div>
        </div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10 relative z-10">
        <!-- Hero Section -->
        <div class="text-center max-w-4xl w-full">
            <!-- Animated Battery Logo with Holographic Effect -->
            <div class="mx-auto mb-10 flex justify-center animate-hologram">
                <div class="battery-animation">
                    <div class="battery-level"></div>
                    <div class="energy-pulse"></div>
                </div>
            </div>

           <h1 class="text-5xl sm:text-6xl font-black mb-6 leading-tight tracking-tight animate-neon-pulse text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-sky-500 to-lime-400">
    REDVERSE E-Bike <span class="text-pink-300 animate-ping">⚡</span>SWAP AND MANAGEMENT SYSTEM
</h1>

            <p class="text-xl text-blue-100/80 mb-10 leading-relaxed max-w-3xl mx-auto animate-circuit-flicker">
                The next-generation <span class="font-semibold text-blue-300">electric mobility platform</span> powering Uganda's sustainable transportation revolution with <span class="font-semibold text-emerald-300">instant battery swaps</span> and <span class="font-semibold text-purple-300">smart energy management</span>.
            </p>

            <!-- Login Orbs Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto mb-16">
                <a href="/rider/login" class="group flex flex-col items-center">
                    <div class="login-orb mb-3 group-hover:scale-110">
                        <i class="fas fa-bolt text-2xl text-blue-400"></i>
                    </div>
                    <span class="font-bold text-blue-300">RIDER</span>
                    <span class="text-xs text-blue-400/70 mt-1">Swap Portal</span>
                </a>
                
                <a href="/agent/login" class="group flex flex-col items-center">
                    <div class="login-orb mb-3 group-hover:scale-110">
                        <i class="fas fa-user-cog text-2xl text-emerald-400"></i>
                    </div>
                    <span class="font-bold text-emerald-300">AGENT</span>
                    <span class="text-xs text-emerald-400/70 mt-1">Station Control</span>
                </a>
                
                <a href="/admin/login" class="group flex flex-col items-center">
                    <div class="login-orb mb-3 group-hover:scale-110">
                        <i class="fas fa-sliders-h text-2xl text-purple-400"></i>
                    </div>
                    <span class="font-bold text-purple-300">ADMIN</span>
                    <span class="text-xs text-purple-400/70 mt-1">System Dashboard</span>
                </a>
                
                <a href="/finance/login" class="group flex flex-col items-center">
                    <div class="login-orb mb-3 group-hover:scale-110">
                        <i class="fas fa-chart-line text-2xl text-amber-400"></i>
                    </div>
                    <span class="font-bold text-amber-300">FINANCE</span>
                    <span class="text-xs text-amber-400/70 mt-1">Analytics Hub</span>
                </a>
            </div>

            <!-- CTA Button -->
            <div class="animate-float">
                <a href="https://redversemobility.com" target="_blank" class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 font-bold text-white hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300 hover:scale-105">
                    <span>Explore Our EV Ecosystem</span>
                    <i class="fas fa-arrow-right animate-pulse"></i>
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-20 max-w-6xl w-full px-4">
            <h2 class="text-center text-3xl font-black mb-12 bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-blue-400">
                POWERING THE FUTURE OF MOBILITY
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="holographic-card p-8 rounded-2xl relative overflow-hidden">
                    <div class="stat-glow" style="--color: #3b82f6;"></div>
                    <div class="flex items-start gap-6">
                        <div class="text-5xl font-black text-blue-400">3.2K</div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Battery Swaps</h3>
                            <p class="text-blue-200/80">Completed across our network</p>
                            <div class="mt-4 h-1 w-full bg-gradient-to-r from-blue-500 to-transparent rounded-full"></div>
                        </div>
                    </div>
                </div>
                
                <div class="holographic-card p-8 rounded-2xl relative overflow-hidden">
                    <div class="stat-glow" style="--color: #10b981;"></div>
                    <div class="flex items-start gap-6">
                        <div class="text-5xl font-black text-emerald-400">99.8%</div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">System Uptime</h3>
                            <p class="text-emerald-200/80">Network reliability</p>
                            <div class="mt-4 h-1 w-full bg-gradient-to-r from-emerald-500 to-transparent rounded-full"></div>
                        </div>
                    </div>
                </div>
                
                <div class="holographic-card p-8 rounded-2xl relative overflow-hidden">
                    <div class="stat-glow" style="--color: #a855f7;"></div>
                    <div class="flex items-start gap-6">
                        <div class="text-5xl font-black text-purple-400">50+</div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Active Agents</h3>
                            <p class="text-purple-200/80">Across 5 districts</p>
                            <div class="mt-4 h-1 w-full bg-gradient-to-r from-purple-500 to-transparent rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="w-full mt-20 px-4">
            <h2 class="text-center text-3xl font-black mb-12 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
                OUR NETWORK COVERAGE
            </h2>
            <div class="max-w-6xl mx-auto w-full h-[500px] rounded-2xl overflow-hidden relative shadow-2xl shadow-blue-500/20">
                <div class="absolute inset-0 bg-gradient-to-b from-blue-500/10 to-transparent z-10 pointer-events-none"></div>
                <div class="map-marker top-1/2 left-1/2"></div>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127644.47038975001!2d32.521477035625325!3d0.3136116922570256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbb6d63e802d1%3A0x5c6f4e987ae3031f!2sKampala!5e0!3m2!1sen!2sug!4v1706183537534!5m2!1sen!2sug"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                
                <!-- Station Finder UI -->
                <div class="absolute bottom-6 left-6 right-6 z-10">
                    <div class="holographic-card p-4 rounded-xl">
                        <div class="flex flex-col sm:flex-row gap-4 items-center">
                            <div class="flex-1 w-full">
                                <div class="relative">
                                    <input type="text" placeholder="Find nearest swap station..." class="w-full bg-gray-900/70 border border-gray-800 rounded-lg py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400">
                                    <button class="absolute right-3 top-3 text-gray-400 hover:text-blue-400 transition">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <button class="bg-gradient-to-r from-blue-600 to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition-all hover:shadow-lg hover:shadow-blue-500/30 whitespace-nowrap">
                                <i class="fas fa-location-arrow mr-2"></i>
                                Detect Location
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-20 w-full max-w-6xl px-4">
            <div class="flex flex-col md:flex-row justify-between items-center py-8 border-t border-gray-800/50">
                <div class="mb-6 md:mb-0">
                    <div class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400 mb-2">REDVERSE</div>
                    <p class="text-sm text-blue-300/70">Electrifying Africa's mobility future</p>
                </div>
                
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition transform hover:-translate-y-1">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition transform hover:-translate-y-1">
                        <i class="fab fa-linkedin-in text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition transform hover:-translate-y-1">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-500 transition transform hover:-translate-y-1">
                        <i class="fab fa-youtube text-xl"></i>
                    </a>
                </div>
            </div>
            
            <div class="text-sm text-center text-gray-500 py-6 border-t border-gray-800/30">
                © {{ date('Y') }} Redvers Technologies. All rights reserved.
            </div>
        </footer>
    </div>

    <!-- Animation Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Create additional floating particles
            const createParticles = () => {
                const container = document.querySelector('.fixed.inset-0');
                for (let i = 0; i < 8; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle absolute rounded-full';
                    
                    // Random properties
                    const size = Math.random() * 4 + 2;
                    const posX = Math.random() * 100;
                    const posY = Math.random() * 100;
                    const delay = Math.random() * 3000;
                    const duration = Math.random() * 6000 + 4000;
                    
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    particle.style.top = `${posY}%`;
                    particle.style.left = `${posX}%`;
                    particle.style.animation = `float ${duration}ms ease-in-out infinite ${delay}ms`;
                    particle.style.backgroundColor = `rgba(59, 130, 246, ${Math.random() * 0.4 + 0.2})`;
                    
                    container.appendChild(particle);
                }
            };
            
            createParticles();
            
            // Animate stats on scroll
            const animateStats = () => {
                const stats = [
                    { id: 'swaps-stat', target: 3200, current: 0, increment: 100 },
                    { id: 'uptime-stat', target: 99.8, current: 0, increment: 1.5 },
                    { id: 'agents-stat', target: 50, current: 0, increment: 2 }
                ];
                
                const interval = setInterval(() => {
                    let allComplete = true;
                    
                    stats.forEach(stat => {
                        if (stat.current < stat.target) {
                            stat.current = Math.min(stat.current + stat.increment, stat.target);
                            document.getElementById(stat.id).textContent = 
                                stat.id === 'uptime-stat' ? stat.current.toFixed(1) : Math.floor(stat.current);
                            allComplete = false;
                        }
                    });
                    
                    if (allComplete) clearInterval(interval);
                }, 50);
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateStats();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            const statsSection = document.querySelector('.max-w-6xl.w-full.px-4:nth-of-type(2)');
            if (statsSection) observer.observe(statsSection);
        });
    </script>
</body>
</html>