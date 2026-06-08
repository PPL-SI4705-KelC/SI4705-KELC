<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Admin Dashboard 🛡️</h1>
            <p class="text-sm text-content-muted">System overview and management</p>
        </div>
    </x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 animate-fade-in">
        <div class="stat-card"><div class="stat-icon bg-primary-100"><svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg></div><div><p class="stat-value">{{ number_format($stats['total_users']) }}</p><p class="stat-label">Total Users</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-secondary-100"><svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div><div><p class="stat-value">{{ number_format($stats['total_activities']) }}</p><p class="stat-label">Activities</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-accent-100"><svg class="w-6 h-6 text-accent-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div><div><p class="stat-value">{{ number_format($stats['total_emissions'], 0) }}</p><p class="stat-label">Total CO₂ (kg)</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-red-100"><svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><p class="stat-value">{{ $stats['pending_blogs'] }}</p><p class="stat-label">Pending Blogs</p></div></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Quick Actions --}}
        <div class="card">
            <h3 class="text-lg font-semibold text-content mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.blogs') }}" class="p-4 rounded-xl bg-accent-50 hover:bg-accent-100 transition-colors text-center">
                    <span class="text-2xl">📝</span>
                    <p class="text-sm font-medium text-content mt-2">Review Blogs</p>
                    @if($stats['pending_blogs'] > 0)<span class="badge-danger mt-1">{{ $stats['pending_blogs'] }} pending</span>@endif
                </a>
                <a href="{{ route('admin.quizzes') }}" class="p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors text-center">
                    <span class="text-2xl">❓</span>
                    <p class="text-sm font-medium text-content mt-2">Manage Quizzes</p>
                </a>
                <a href="{{ route('admin.users') }}" class="p-4 rounded-xl bg-primary-50 hover:bg-primary-100 transition-colors text-center">
                    <span class="text-2xl">👥</span>
                    <p class="text-sm font-medium text-content mt-2">Manage Users</p>
                </a>
                <a href="{{ route('admin.leaderboard') }}" class="p-4 rounded-xl bg-secondary-50 hover:bg-secondary-100 transition-colors text-center">
                    <span class="text-2xl">🏆</span>
                    <p class="text-sm font-medium text-content mt-2">Leaderboard</p>
                </a>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="card">
            <h3 class="text-lg font-semibold text-content mb-4">Recent Users</h3>
            <div class="space-y-3">
                @foreach($recentUsers as $u)
                <div class="flex items-center gap-3 py-2">
                    <div class="avatar-primary text-xs">{{ substr($u->name, 0, 2) }}</div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-content">{{ $u->name }}</p>
                        <p class="text-xs text-content-muted">{{ $u->email }} · {{ $u->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="badge {{ $u->role === 'admin' ? 'badge-primary' : 'badge-secondary' }}">{{ $u->role }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
