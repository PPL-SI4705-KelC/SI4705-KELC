<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Act4Climate - Track your carbon footprint, take daily quizzes, and join a community of climate advocates.">
    <title>Act4Climate — Track Your Carbon Impact</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js (Included via Vite usually, but added explicitly for standalone interactions if needed) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .floating-orb {
            animation: float 8s ease-in-out infinite;
        }

        .floating-orb-delayed {
            animation: float 10s ease-in-out infinite 2s;
        }

        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
            100% { transform: translateY(0px) scale(1); }
        }

        /* Scroll reveal animation utilities */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .hover-tilt {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-tilt:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px -5px rgba(45, 90, 76, 0.15);
        }
    </style>
</head>
<body class="font-sans antialiased bg-surface text-content overflow-x-hidden" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    
    {{-- Navigation --}}
    <nav :class="{'glass-nav shadow-sm': scrolled, 'bg-transparent': !scrolled}" class="fixed top-0 w-full z-50 transition-all duration-300 py-4">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <x-application-logo class="transform group-hover:scale-105 transition-transform" />
            </a>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="#features" class="text-content-body hover:text-primary transition-colors">Features</a>
                <a href="#journey" class="text-content-body hover:text-primary transition-colors">Journey</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm shadow-lg shadow-primary/30 hover:scale-105 transition-transform">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:block text-sm font-semibold text-primary hover:text-primary-700 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm shadow-lg shadow-primary/30 hover:scale-105 transition-transform">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        {{-- Animated Background Orbs --}}
        <div class="absolute inset-0 bg-gradient-to-br from-surface via-primary-50 to-secondary-50 z-0"></div>
        <div class="absolute top-1/4 -left-32 w-96 h-96 bg-secondary-300/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 floating-orb z-0"></div>
        <div class="absolute top-1/3 -right-32 w-96 h-96 bg-accent-300/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 floating-orb-delayed z-0"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-primary-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 floating-orb z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 text-center flex flex-col items-center">
            <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md px-5 py-2.5 rounded-full text-primary-800 text-sm font-semibold mb-8 border border-white/50 shadow-sm animate-slide-up" style="animation-delay: 0.1s;">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary-500"></span>
                </span>
                Join 1,000+ climate advocates today
            </div>
            
            <h1 class="text-5xl sm:text-7xl font-black text-content leading-[1.1] tracking-tight max-w-4xl animate-slide-up" style="animation-delay: 0.2s;">
                Make Every Action <br/>
                Count for the <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-secondary-500 relative">
                    Planet
                    <svg class="absolute w-full h-3 -bottom-1 left-0 text-secondary-400 opacity-60" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="4" fill="none"/></svg>
                </span>
            </h1>
            
            <p class="mt-8 text-lg sm:text-xl text-content-body max-w-2xl mx-auto leading-relaxed animate-slide-up" style="animation-delay: 0.3s;">
                An interactive platform to track your carbon footprint, learn through daily quizzes, earn XP, and connect with a community making real-world impact.
            </p>
            
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-5 animate-slide-up" style="animation-delay: 0.4s;">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-primary text-white text-lg font-bold rounded-2xl shadow-xl shadow-primary/30 hover:bg-primary-700 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    Start Your Journey
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white text-content text-lg font-bold rounded-2xl shadow-md border border-surface-border hover:bg-gray-50 hover:-translate-y-1 transition-all duration-300">
                    Explore Features
                </a>
            </div>

            {{-- Article Preview Mockup --}}
            <div class="mt-20 w-full max-w-4xl relative animate-slide-up" style="animation-delay: 0.6s;">
                <div class="absolute inset-0 bg-gradient-to-t from-surface z-10 top-1/2 pointer-events-none"></div>
                <div class="glass-card rounded-3xl p-3 sm:p-4 shadow-2xl transform perspective-1000 rotate-x-12 hover:rotate-x-0 transition-transform duration-700 ease-out border border-white/40">
                    <div class="bg-white rounded-[1.25rem] overflow-hidden border border-gray-100 shadow-inner flex flex-col md:flex-row text-left">
                        <div class="w-full md:w-5/12 h-64 md:h-auto relative overflow-hidden shrink-0">
                            <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=800" alt="Nature Article" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur-sm text-[#2A5C4D] text-[11px] font-bold px-3 py-1.5 rounded-full shadow-sm uppercase tracking-wider">
                                    Featured Guide
                                </span>
                            </div>
                        </div>
                        <div class="p-8 md:p-10 flex flex-col justify-center flex-1">
                            <div class="flex items-center gap-2 text-xs font-bold text-gray-400 mb-3 tracking-wider uppercase">
                                <span class="text-[#2A5C4D]">Climate Action</span>
                                <span>•</span>
                                <span>5 min read</span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-4 leading-tight tracking-tight">
                                5 Practical Ways to Reduce Your Daily Carbon Footprint
                            </h3>
                            <p class="text-gray-500 mb-8 leading-relaxed text-sm">
                                Discover simple, actionable steps you can take in your everyday life to significantly lower your environmental impact and contribute to a healthier, greener planet.
                            </p>
                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=E2E8F0&color=2A5C4D" alt="Author" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">Sarah Johnson</p>
                                        <p class="text-xs font-medium text-gray-500">Eco Warrior</p>
                                    </div>
                                </div>
                                <a href="{{ route('login') }}" class="text-[#2A5C4D] font-bold text-sm hover:underline flex items-center gap-1 transition-colors hover:text-[#1e4237]">
                                    Read Blogs
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive Features --}}
    <section id="features" class="py-32 relative z-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20 reveal">
                <span class="text-secondary-600 font-bold tracking-wider uppercase text-sm mb-3 block">Toolkit</span>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-content">Everything you need to <br><span class="text-primary">make an impact</span></h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                $features = [
                    ['icon' => '🌍', 'title' => 'Carbon Calculator', 'desc' => 'Track your daily emissions across transport, consumption, and energy with real-time feedback.', 'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
                    ['icon' => '📊', 'title' => 'Visual Analytics', 'desc' => 'Understand your footprint through interactive charts, 30-day trends, and personalized insights.', 'color' => 'bg-green-50 text-green-600 border-green-100'],
                    ['icon' => '🏆', 'title' => 'Gamification', 'desc' => 'Earn XP, level up, and unlock achievements. Turn climate action into an engaging journey.', 'color' => 'bg-amber-50 text-amber-600 border-amber-100'],
                    ['icon' => '❓', 'title' => 'Daily Quizzes', 'desc' => 'Test your knowledge on climate change, SDGs, and environment. Build streaks for bonus XP.', 'color' => 'bg-purple-50 text-purple-600 border-purple-100'],
                    ['icon' => '📝', 'title' => 'Climate Blogs', 'desc' => 'Read inspiring stories or publish your own eco-journey. Gain XP for approved articles.', 'color' => 'bg-pink-50 text-pink-600 border-pink-100'],
                    ['icon' => '🤝', 'title' => 'Community Hub', 'desc' => 'Join eco-groups, share updates, and collaborate with a network of planet guardians.', 'color' => 'bg-teal-50 text-teal-600 border-teal-100'],
                ];
                @endphp
                
                @foreach($features as $index => $f)
                <div class="glass-card hover-tilt rounded-3xl p-8 border relative overflow-hidden group reveal" style="transition-delay: {{ $index * 100 }}ms;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-white/40 to-transparent rounded-bl-full z-0 pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl {{ $f['color'] }} flex items-center justify-center text-2xl mb-6 shadow-sm transform group-hover:rotate-6 transition-transform">
                            {{ $f['icon'] }}
                        </div>
                        <h3 class="text-xl font-bold text-content mb-3">{{ $f['title'] }}</h3>
                        <p class="text-content-body leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Journey Section (Interactive steps) --}}
    <section id="journey" class="py-32 bg-surface border-y border-surface-border overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <h2 class="text-4xl font-extrabold text-content mb-6">Level up your <span class="text-secondary-600">Eco Status</span></h2>
                    <p class="text-lg text-content-body mb-8">Every positive action you log, quiz you pass, and blog you write earns you XP. Climb the ranks from an Eco Beginner to a true Planet Guardian.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-sm border border-surface-border hover-tilt">
                            <div class="w-12 h-12 bg-primary-100 text-primary-700 rounded-xl flex items-center justify-center font-bold text-xl">1</div>
                            <div>
                                <h4 class="font-bold text-content">Log Emissions</h4>
                                <p class="text-sm text-content-muted">Track your daily impact</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-sm border border-surface-border hover-tilt">
                            <div class="w-12 h-12 bg-secondary-100 text-secondary-700 rounded-xl flex items-center justify-center font-bold text-xl">2</div>
                            <div>
                                <h4 class="font-bold text-content">Complete Quizzes</h4>
                                <p class="text-sm text-content-muted">Learn and earn XP daily</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-sm border border-surface-border hover-tilt">
                            <div class="w-12 h-12 bg-accent-100 text-accent-700 rounded-xl flex items-center justify-center font-bold text-xl">3</div>
                            <div>
                                <h4 class="font-bold text-content">Engage Community</h4>
                                <p class="text-sm text-content-muted">Write blogs & interact</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative reveal flex items-center justify-center py-4">
                    <div class="absolute inset-0 bg-gradient-to-tr from-secondary-200 to-primary-200 rounded-full blur-3xl opacity-20"></div>
                    <div class="relative grid grid-cols-2 gap-5 w-full max-w-md">
                        {{-- Level 1 --}}
                        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-border hover:shadow-elevated hover:-translate-y-1 transition-all duration-300">
                            <div class="text-3xl mb-3">🌱</div>
                            <div class="font-bold text-base text-content">Level 1</div>
                            <div class="text-sm text-content-muted mt-0.5">Eco Beginner</div>
                            <div class="mt-4 w-full bg-gray-100 rounded-full h-2"><div class="bg-primary h-2 rounded-full" style="width: 100%"></div></div>
                        </div>
                        {{-- Level 2 --}}
                        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-border hover:shadow-elevated hover:-translate-y-1 transition-all duration-300">
                            <div class="text-3xl mb-3">🌿</div>
                            <div class="font-bold text-base text-content">Level 2</div>
                            <div class="text-sm text-content-muted mt-0.5">Green Starter</div>
                            <div class="mt-4 w-full bg-gray-100 rounded-full h-2"><div class="bg-secondary h-2 rounded-full" style="width: 60%"></div></div>
                        </div>
                        {{-- Level 3 --}}
                        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-border hover:shadow-elevated hover:-translate-y-1 transition-all duration-300">
                            <div class="text-3xl mb-3">🌍</div>
                            <div class="font-bold text-base text-content">Level 3</div>
                            <div class="text-sm text-content-muted mt-0.5">Eco Warrior</div>
                            <div class="mt-4 w-full bg-gray-100 rounded-full h-2"><div class="bg-gray-300 h-2 rounded-full" style="width: 0%"></div></div>
                        </div>
                        {{-- Level 6 --}}
                        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-border hover:shadow-elevated hover:-translate-y-1 transition-all duration-300 opacity-60">
                            <div class="text-3xl mb-3">🌟</div>
                            <div class="font-bold text-base text-content">Level 6</div>
                            <div class="text-sm text-content-muted mt-0.5">Planet Guardian</div>
                            <div class="mt-4 w-full bg-gray-100 rounded-full h-2"><div class="bg-gray-300 h-2 rounded-full" style="width: 0%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-primary-900"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-secondary-500/20 rounded-full filter blur-[100px] translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center reveal">
            <h2 class="text-4xl sm:text-6xl font-black text-white mb-6">Ready to make a difference?</h2>
            <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto">Start tracking your carbon footprint today. Join a community dedicated to a greener, more sustainable future.</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-accent-500 hover:bg-accent-400 text-accent-900 text-lg font-bold rounded-2xl shadow-xl shadow-accent-500/30 transition-all hover:scale-105">
                    Create Account
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white text-lg font-bold rounded-2xl backdrop-blur-sm border border-white/20 transition-all">
                     Login
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-950 py-12 text-gray-400">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6 opacity-80 brightness-200 grayscale contrast-200">
                    <x-application-logo />
                </div>
                <p class="text-sm max-w-md">Empowering individuals to measure, reduce, and share their carbon footprint for a sustainable future.</p>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4">Platform</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Carbon Calculator</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Daily Quizzes</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Climate Blogs</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Community</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4">Legal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row items-center justify-between">
            <p class="text-sm">&copy; {{ date('Y') }} Act4Climate. All rights reserved.</p>
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="#" class="text-gray-400 hover:text-white transition-colors"><span class="sr-only">Twitter</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors"><span class="sr-only">GitHub</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>
    </footer>

    {{-- Scroll Animation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('.reveal');
            
            const revealOnScroll = () => {
                const windowHeight = window.innerHeight;
                const elementVisible = 100;
                
                reveals.forEach((reveal) => {
                    const elementTop = reveal.getBoundingClientRect().top;
                    if (elementTop < windowHeight - elementVisible) {
                        reveal.classList.add('active');
                    }
                });
            };

            window.addEventListener('scroll', revealOnScroll);
            revealOnScroll(); // Trigger once on load
        });
    </script>
</body>
</html>
