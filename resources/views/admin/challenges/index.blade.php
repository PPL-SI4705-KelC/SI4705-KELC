<x-app-layout>
    <x-slot name="title">Manage Challenges</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Manage Challenges 🎯</h1>
            <p class="text-sm text-content-muted">Create, edit and track user engagement in climate challenges</p>
        </div>
    </x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="stat-card">
            <div class="stat-icon bg-primary-100">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">3</p>
                <p class="stat-label">Active Challenges</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-secondary-100">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">307</p>
                <p class="stat-label">Total Participants</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-accent-100">
                <svg class="w-6 h-6 text-accent-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-value">68%</p>
                <p class="stat-label">Avg. Completion Rate</p>
            </div>
        </div>
    </div>

    {{-- List of Challenges --}}
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-content">Active Challenges</h3>
            <button class="btn-primary flex items-center gap-2">
                <span>+</span> Create Challenge
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-xs font-semibold text-content-muted uppercase">
                        <th class="pb-3">Title & Category</th>
                        <th class="pb-3">Duration</th>
                        <th class="pb-3">Reward</th>
                        <th class="pb-3">Participants</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach($challenges as $c)
                    <tr>
                        <td class="py-4">
                            <span class="font-bold text-gray-900 block">{{ $c['title'] }}</span>
                            <span class="text-xs text-content-muted mt-0.5 block">{{ $c['category'] }}</span>
                        </td>
                        <td class="py-4 text-content-body font-medium">{{ $c['duration'] }}</td>
                        <td class="py-4 text-primary font-bold">{{ $c['xp_reward'] }} XP</td>
                        <td class="py-4 text-content-body font-medium">{{ $c['participants_count'] }} users</td>
                        <td class="py-4 text-right">
                            <button class="text-xs font-bold text-primary hover:text-primary-600 mr-3 transition-colors">Edit</button>
                            <button class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors">Disable</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
