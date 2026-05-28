<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
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
            </div>
        </div>

        {{-- Activities --}}
        <div class="stat-card">
            <div class="stat-icon bg-secondary-100">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">{{ number_format($stats['total_activities']) }}</p>
                <p class="stat-label">Activities</p>
            </div>
        </div>

        {{-- Total CO2 --}}
        <div class="stat-card">
            <div class="stat-icon bg-accent-100">
                <span class="text-xs font-black text-accent-700 uppercase tracking-tighter select-none">co₂</span>
            </div>
            <div>
                <p class="stat-value">{{ number_format($stats['total_emissions']) }}</p>
                <p class="stat-label">Total CO₂ (kg)</p>
            </div>
        </div>

        {{-- Pending Blogs --}}
        <div class="stat-card">
            <div class="stat-icon bg-red-100">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">{{ $stats['pending_blogs'] }}</p>
                <p class="stat-label">Pending Blogs</p>
            </div>
        </div>
    </div>

    {{-- Mid section: Quick Actions & Recent Users --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Quick Actions (2/3 width) --}}
        <div class="card lg:col-span-2 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-content mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    {{-- Review Blogs --}}
                    <a href="{{ route('admin.blogs') }}" class="p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/20 hover:bg-white hover:shadow-card transition-all text-center flex flex-col items-center justify-center">
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
                </div>
            </div>
        </div>

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
                </div>
                @endforeach
            </div>
        </div>
    </div>

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
</x-app-layout>
