<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
<<<<<<< HEAD
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Admin Dashboard 🛡️</h1>
            <p class="text-sm text-content-muted">System overview and environmental impact management</p>
        </div>
    </x-slot>

    {{-- Stats Cards Grid (Mockup-aligned but consistent with design tokens) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 animate-fade-in">
        {{-- Total Users --}}
        <div class="stat-card">
            <div class="stat-icon bg-primary-100">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">{{ number_format($stats['total_users']) }}</p>
                <p class="stat-label">Total Users</p>
=======


    <style>
        /* Dashboard-specific premium styles */
        .dashboard-banner {
            background: linear-gradient(135deg, #1B3A30 0%, #2D5A4C 40%, #3D8C40 100%);
            position: relative;
            overflow: hidden;
        }
        .dashboard-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        .dashboard-banner::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(76,175,80,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stat-card-premium {
            background: white;
            border: 1px solid #E0E0E0;
            border-radius: 16px;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card-premium:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .stat-card-premium .stat-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 16px 16px 0 0;
        }
        .stat-card-premium .stat-bg-pattern {
            position: absolute;
            right: -15px;
            bottom: -15px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            opacity: 0.06;
            transition: transform 0.5s ease;
        }
        .stat-card-premium:hover .stat-bg-pattern {
            transform: scale(1.3);
        }

        .action-tile {
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }
        .action-tile::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .action-tile:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(45, 90, 76, 0.12);
        }
        .action-tile:hover::before {
            opacity: 1;
        }

        .user-row {
            transition: all 0.25s ease;
            border-radius: 12px;
        }
        .user-row:hover {
            background: linear-gradient(135deg, #f0faf5 0%, #f8f9fa 100%);
            transform: translateX(4px);
        }

        .mini-stat {
            background: white;
            border: 1px solid #E0E0E0;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .mini-stat::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            border-radius: 16px 0 0 16px;
        }
        .mini-stat:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        /* Staggered animation */
        .stagger-1 { animation: dashSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.05s forwards; opacity: 0; }
        .stagger-2 { animation: dashSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards; opacity: 0; }
        .stagger-3 { animation: dashSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards; opacity: 0; }
        .stagger-4 { animation: dashSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; opacity: 0; }
        .stagger-5 { animation: dashSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.28s forwards; opacity: 0; }
        .stagger-6 { animation: dashSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.36s forwards; opacity: 0; }
        .stagger-7 { animation: dashSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.44s forwards; opacity: 0; }

        @keyframes dashSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes dashFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
    </style>

    {{-- Welcome Banner --}}
    <div class="dashboard-banner rounded-2xl p-6 sm:p-8 mb-8 stagger-1">
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
                        <span class="text-xl floating-icon inline-block">🌿</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Welcome back, {{ Auth::user()->name }}</h2>
                </div>
                <p class="text-white/70 text-sm sm:text-base ml-[52px]">Here's what's happening with Act4Climate today.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-white/80 bg-white/10 backdrop-blur-md rounded-xl px-4 py-2.5 border border-white/10">
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="font-medium">{{ now()->format('l, d M Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Primary Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
        {{-- Total Users --}}
        <div class="stat-card-premium group stagger-2">
            <div class="stat-glow bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600" style="animation: pulseGlow 3s ease-in-out infinite;"></div>
            <div class="stat-bg-pattern bg-primary-500"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-2xl font-extrabold text-content leading-none tracking-tight">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-xs font-medium text-content-muted mt-1.5 uppercase tracking-wider">Total Users</p>
                </div>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
            </div>
        </div>

        {{-- Activities --}}
<<<<<<< HEAD
        <div class="stat-card">
            <div class="stat-icon bg-secondary-100">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">{{ number_format($stats['total_activities']) }}</p>
                <p class="stat-label">Activities</p>
=======
        <div class="stat-card-premium group stagger-3">
            <div class="stat-glow bg-gradient-to-r from-secondary-400 via-secondary-500 to-secondary-600" style="animation: pulseGlow 3s ease-in-out infinite 0.5s;"></div>
            <div class="stat-bg-pattern bg-secondary-500"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary-50 to-secondary-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-2xl font-extrabold text-content leading-none tracking-tight">{{ number_format($stats['total_activities']) }}</p>
                    <p class="text-xs font-medium text-content-muted mt-1.5 uppercase tracking-wider">Activities</p>
                </div>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
            </div>
        </div>

        {{-- Total CO2 --}}
<<<<<<< HEAD
        <div class="stat-card">
            <div class="stat-icon bg-accent-100">
                <span class="text-xs font-black text-accent-700 uppercase tracking-tighter select-none">co₂</span>
            </div>
            <div>
                <p class="stat-value">{{ number_format($stats['total_emissions']) }}</p>
                <p class="stat-label">Total CO₂ (kg)</p>
=======
        <div class="stat-card-premium group stagger-4">
            <div class="stat-glow bg-gradient-to-r from-accent-400 via-accent-500 to-accent-600" style="animation: pulseGlow 3s ease-in-out infinite 1s;"></div>
            <div class="stat-bg-pattern bg-accent-500"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-50 to-accent-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <span class="text-xs font-black text-accent-700 uppercase tracking-tighter select-none">co₂</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-2xl font-extrabold text-content leading-none tracking-tight">{{ number_format($stats['total_emissions']) }}</p>
                    <p class="text-xs font-medium text-content-muted mt-1.5 uppercase tracking-wider">Total CO₂ (kg)</p>
                </div>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
            </div>
        </div>

        {{-- Pending Blogs --}}
<<<<<<< HEAD
        <div class="stat-card">
            <div class="stat-icon bg-red-100">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">{{ $stats['pending_blogs'] }}</p>
                <p class="stat-label">Pending Blogs</p>
=======
        <div class="stat-card-premium group stagger-4">
            <div class="stat-glow bg-gradient-to-r from-red-400 via-red-500 to-red-600" style="animation: pulseGlow 3s ease-in-out infinite 1.5s;"></div>
            <div class="stat-bg-pattern bg-red-500"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-2xl font-extrabold text-content leading-none tracking-tight">{{ $stats['pending_blogs'] }}</p>
                    <p class="text-xs font-medium text-content-muted mt-1.5 uppercase tracking-wider">Pending Blogs</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Mini Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 stagger-5">
        <div class="mini-stat" style="--stripe-color: #2D5A4C;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#2D5A4C;border-radius:16px 0 0 16px;"></div>
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-lg font-extrabold text-content leading-none">{{ number_format($stats['total_blogs']) }}</p>
                <p class="text-[11px] text-content-muted mt-0.5 font-medium uppercase tracking-wider">Total Blogs</p>
            </div>
        </div>
        <div class="mini-stat">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#4CAF50;border-radius:16px 0 0 16px;"></div>
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-secondary-50 to-secondary-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-lg font-extrabold text-content leading-none">{{ number_format($stats['total_communities']) }}</p>
                <p class="text-[11px] text-content-muted mt-0.5 font-medium uppercase tracking-wider">Communities</p>
            </div>
        </div>
        <div class="mini-stat">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#FBC02D;border-radius:16px 0 0 16px;"></div>
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-accent-50 to-accent-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-accent-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-lg font-extrabold text-content leading-none">{{ number_format($stats['avg_emission'], 1) }}</p>
                <p class="text-[11px] text-content-muted mt-0.5 font-medium uppercase tracking-wider">Avg CO₂/User (kg)</p>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
            </div>
        </div>
    </div>

    {{-- Mid section: Quick Actions & Recent Users --}}
<<<<<<< HEAD
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Quick Actions (2/3 width) --}}
        <div class="card lg:col-span-2 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-content mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    {{-- Review Blogs --}}
                    <a href="{{ route('admin.blogs.index') }}" class="p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/20 hover:bg-white hover:shadow-card transition-all text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary mb-3 shadow-sm shrink-0">
                            <span class="text-xl">📝</span>
                        </div>
                        <p class="text-xs font-semibold text-content leading-tight">Review Blogs</p>
                        @if($stats['pending_blogs'] > 0)
                            <span class="badge-danger mt-1.5 text-[10px]">{{ $stats['pending_blogs'] }} pending</span>
                        @endif
                    </a>

                    {{-- Manage Quizzes --}}
                    <a href="{{ route('admin.quizzes') }}" class="p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/20 hover:bg-white hover:shadow-card transition-all text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary mb-3 shadow-sm shrink-0">
                            <span class="text-xl">❓</span>
                        </div>
                        <p class="text-xs font-semibold text-content leading-tight">Manage Quizzes</p>
                    </a>

                    {{-- Manage Communities --}}
                    <a href="{{ route('admin.communities') }}" class="p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/20 hover:bg-white hover:shadow-card transition-all text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary mb-3 shadow-sm shrink-0">
                            <span class="text-xl">👥</span>
                        </div>
                        <p class="text-xs font-semibold text-content leading-tight">Manage Communities</p>
                    </a>

                    {{-- Manage Users --}}
                    <a href="{{ route('admin.users') }}" class="p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/20 hover:bg-white hover:shadow-card transition-all text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary mb-3 shadow-sm shrink-0">
                            <span class="text-xl">👤</span>
                        </div>
                        <p class="text-xs font-semibold text-content leading-tight">Manage Users</p>
                    </a>

                    {{-- Leaderboard --}}
                    <a href="{{ route('admin.leaderboard') }}" class="p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/20 hover:bg-white hover:shadow-card transition-all text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary mb-3 shadow-sm shrink-0">
                            <span class="text-xl">🏆</span>
                        </div>
                        <p class="text-xs font-semibold text-content leading-tight">Leaderboard</p>
                    </a>

                    {{-- Add Action (Dashed) --}}
                    <div class="p-5 rounded-2xl bg-white border border-dashed border-gray-200 text-center flex flex-col items-center justify-center cursor-not-allowed opacity-60">
                      <div class="w-12 h-12 rounded-full bg-white border border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-3 shrink-0">
                          <span class="text-lg font-bold">+</span>
                      </div>
                      <p class="text-xs font-semibold text-gray-400 leading-tight">Add Action</p>
                    </div>
=======
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-8">
        {{-- Quick Actions (3/5 width) --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-surface-border p-6 stagger-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-content flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-primary-600 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    Quick Actions
                </h3>
                <span class="text-[10px] text-content-muted font-medium uppercase tracking-widest">Navigate</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                {{-- Review Blogs --}}
                <a href="{{ route('admin.blogs.index') }}" class="action-tile bg-gradient-to-br from-white to-primary-50/40 border-gray-100 hover:border-primary-200" style="border-color: #f3f4f6;">
                    <div class="relative w-12 h-12 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200/60 flex items-center justify-center shadow-sm group-hover:shadow transition-all duration-300">
                        <span class="text-xl">📝</span>
                        @if($stats['pending_blogs'] > 0)
                            <span class="absolute -top-1.5 -right-1.5 min-w-[20px] h-[20px] rounded-full bg-gradient-to-br from-red-500 to-red-600 text-white text-[9px] font-black flex items-center justify-center shadow-md" style="animation: pulseGlow 2s ease-in-out infinite;">{{ $stats['pending_blogs'] }}</span>
                        @endif
                    </div>
                    <p class="text-xs font-bold text-content leading-tight">Review Blogs</p>
                </a>

                {{-- Manage Quizzes --}}
                <a href="{{ route('admin.quizzes') }}" class="action-tile bg-gradient-to-br from-white to-secondary-50/40 border-gray-100 hover:border-secondary-200" style="border-color: #f3f4f6;">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary-100 to-secondary-200/60 flex items-center justify-center shadow-sm">
                        <span class="text-xl">❓</span>
                    </div>
                    <p class="text-xs font-bold text-content leading-tight">Manage Quizzes</p>
                </a>

                {{-- Manage Communities --}}
                <a href="{{ route('admin.communities') }}" class="action-tile bg-gradient-to-br from-white to-accent-50/40 border-gray-100 hover:border-accent-200" style="border-color: #f3f4f6;">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-100 to-accent-200/60 flex items-center justify-center shadow-sm">
                        <span class="text-xl">👥</span>
                    </div>
                    <p class="text-xs font-bold text-content leading-tight">Communities</p>
                </a>

                {{-- Manage Users --}}
                <a href="{{ route('admin.users') }}" class="action-tile bg-gradient-to-br from-white to-primary-50/40 border-gray-100 hover:border-primary-200" style="border-color: #f3f4f6;">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200/60 flex items-center justify-center shadow-sm">
                        <span class="text-xl">👤</span>
                    </div>
                    <p class="text-xs font-bold text-content leading-tight">Manage Users</p>
                </a>

                {{-- Leaderboard --}}
                <a href="{{ route('admin.leaderboard') }}" class="action-tile bg-gradient-to-br from-white to-accent-50/40 border-gray-100 hover:border-accent-200" style="border-color: #f3f4f6;">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-100 to-accent-200/60 flex items-center justify-center shadow-sm">
                        <span class="text-xl">🏆</span>
                    </div>
                    <p class="text-xs font-bold text-content leading-tight">Leaderboard</p>
                </a>

                {{-- Quizzes Taken (info tile) --}}
                <div class="action-tile" style="background: linear-gradient(135deg, #E8F0ED 0%, #D1E1DB 100%); border-color: #A3C3B7;">
                    <div class="w-12 h-12 rounded-xl bg-white/80 flex items-center justify-center shadow-sm">
                        <span class="text-xl">📊</span>
                    </div>
                    <p class="text-xs font-bold text-content leading-tight">Quizzes Taken</p>
                    <p class="text-xl font-extrabold text-primary leading-none tracking-tight">{{ number_format($stats['total_quizzes_taken']) }}</p>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
                </div>
            </div>
        </div>

<<<<<<< HEAD
        {{-- Recent Users (1/3 width) --}}
        <div class="card flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-content">Recent Users</h3>
                <a href="{{ route('admin.users') }}" class="text-xs font-bold text-primary hover:text-primary-600 transition-colors uppercase tracking-wider">View All</a>
            </div>
            <div class="space-y-4 flex-1 flex flex-col justify-start">
                @foreach($recentUsers as $u)
                <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="avatar-primary text-xs shrink-0 select-none">{{ substr($u->name, 0, 2) }}</div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-content truncate max-w-[130px] sm:max-w-[170px] leading-tight">{{ $u->name }}</p>
                            <p class="text-xs text-content-muted mt-0.5 truncate max-w-[130px] sm:max-w-[170px]">{{ $u->email }}</p>
                        </div>
                    </div>
                    <span class="badge {{ $u->role === 'admin' ? 'badge-primary' : 'badge-secondary' }}">{{ $u->role }}</span>
=======
        {{-- Recent Users (2/5 width) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-surface-border p-6 flex flex-col stagger-7">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-content flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-secondary to-secondary-600 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                    </div>
                    Recent Users
                </h3>
                <a href="{{ route('admin.users') }}" class="text-[10px] font-bold text-primary hover:text-primary-600 transition-colors uppercase tracking-widest flex items-center gap-1 bg-primary-50 px-3 py-1.5 rounded-lg hover:bg-primary-100">
                    View All
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="space-y-2 flex-1">
                @foreach($recentUsers as $idx => $u)
                <div class="user-row flex items-center justify-between p-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold shrink-0 select-none shadow-sm
                            {{ $idx % 3 === 0 ? 'bg-gradient-to-br from-primary-100 to-primary-200 text-primary-700' : ($idx % 3 === 1 ? 'bg-gradient-to-br from-secondary-100 to-secondary-200 text-secondary-700' : 'bg-gradient-to-br from-accent-100 to-accent-200 text-accent-700') }}">
                            {{ strtoupper(substr($u->name, 0, 2)) }}
                        </div>
                        <div class="overflow-hidden min-w-0">
                            <p class="text-sm font-bold text-content truncate leading-tight">{{ $u->name }}</p>
                            <p class="text-[11px] text-content-muted mt-0.5 truncate">{{ $u->email }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 ml-2 inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                        {{ $u->role === 'admin' ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'bg-secondary-50 text-secondary-700 border border-secondary-200' }}">
                        {{ $u->role }}
                    </span>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
                </div>
                @endforeach
            </div>
        </div>
    </div>

<<<<<<< HEAD
    {{-- Bottom Section: Regional Carbon Tracking & Platform Health --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
        {{-- Regional Carbon Tracking (2/3 width) --}}
        <div class="lg:col-span-2 relative rounded-2xl overflow-hidden min-h-[240px] flex flex-col justify-between p-6 text-white border border-surface-border">
            {{-- Background Image --}}
            <div class="absolute inset-0 z-0 select-none">
                <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=1200" alt="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/45 to-transparent"></div>
            </div>

            <div class="relative z-10 max-w-md mt-2">
                <h3 class="text-xl font-bold tracking-tight text-white leading-tight">Regional Carbon Tracking</h3>
                <p class="text-white/80 text-xs mt-2 leading-relaxed">
                    Monitoring the reduction of greenhouse gases across global pilot communities. Data refreshed every 24 hours.
                </p>
            </div>

            <div class="relative z-10 flex gap-12 mt-6">
                <div>
                    <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider">Reduction</p>
                    <p class="text-2xl font-bold text-white mt-1">-12.4%</p>
                </div>
                <div>
                    <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider">Target</p>
                    <p class="text-2xl font-bold text-white mt-1">25.0%</p>
                </div>
            </div>
        </div>

        {{-- Platform Health (1/3 width) --}}
        <div class="bg-primary rounded-2xl p-6 text-white border border-primary-700 shadow-card flex flex-col justify-between min-h-[240px]">
            <div class="space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 3.5 1 9.8a7 7 0 0 1-9 8.2z"></path>
                        <path d="M9 22v-4"></path>
                    </svg>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold tracking-tight text-white leading-tight">Platform Health</h3>
                    <p class="text-white/80 text-xs mt-2 leading-relaxed">
                        All systems are operational. Data nodes synchronized globally.
                    </p>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <div class="progress-bar bg-white/20">
                    <div class="progress-fill bg-white" style="width: 98.9%"></div>
                </div>
                <p class="text-[10px] font-bold text-white uppercase tracking-widest">98.9% UPTIME</p>
            </div>
        </div>
    </div>
=======
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
</x-app-layout>
