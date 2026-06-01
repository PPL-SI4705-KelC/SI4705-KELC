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

</x-app-layout>
