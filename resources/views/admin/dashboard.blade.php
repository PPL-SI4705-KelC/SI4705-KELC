<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>


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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
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
            </div>
        </div>

        {{-- Total Blogs --}}
        <div class="stat-card-premium group stagger-3">
            <div class="stat-glow bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-600" style="animation: pulseGlow 3s ease-in-out infinite 0.5s;"></div>
            <div class="stat-bg-pattern bg-emerald-500"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-2xl font-extrabold text-content leading-none tracking-tight">{{ number_format($stats['total_blogs']) }}</p>
                    <p class="text-xs font-medium text-content-muted mt-1.5 uppercase tracking-wider">Total Blogs</p>
                </div>
            </div>
        </div>

        {{-- Communities --}}
        <div class="stat-card-premium group stagger-4">
            <div class="stat-glow bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600" style="animation: pulseGlow 3s ease-in-out infinite 1s;"></div>
            <div class="stat-bg-pattern bg-blue-500"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-2xl font-extrabold text-content leading-none tracking-tight">{{ number_format($stats['total_communities']) }}</p>
                    <p class="text-xs font-medium text-content-muted mt-1.5 uppercase tracking-wider">Communities</p>
                </div>
            </div>
        </div>

        {{-- Pending Blogs --}}
        <div class="stat-card-premium group stagger-5">
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

    {{-- Mid section: Quick Actions & Recent Users --}}
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
                        <svg class="w-6 h-6 text-[#2D5A4C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
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

            </div>
        </div>

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
                        <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center shrink-0 select-none shadow-sm
                            {{ !$u->avatar ? ($idx % 3 === 0 ? 'bg-gradient-to-br from-primary-100 to-primary-200 text-primary-700' : ($idx % 3 === 1 ? 'bg-gradient-to-br from-secondary-100 to-secondary-200 text-secondary-700' : 'bg-gradient-to-br from-accent-100 to-accent-200 text-accent-700')) : '' }}">
                            @if($u->avatar)
                                <img src="{{ asset('storage/' . $u->avatar) }}" alt="{{ $u->name }}'s Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-bold">{{ strtoupper(substr($u->name, 0, 2)) }}</span>
                            @endif
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
                </div>
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>
