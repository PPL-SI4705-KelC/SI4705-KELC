<x-app-layout>
    <x-slot name="title">Leaderboard</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">🏆 Leaderboard</h1>
    </x-slot>

    <div class="max-w-2xl card animate-fade-in">
        <div class="space-y-2">
            @foreach($leaderboard as $i => $u)
            @php
                $rank = $u->rank ?? ($i + 1);
            @endphp
            <div class="flex items-center gap-4 p-3 rounded-xl {{ $rank <= 3 ? 'bg-accent-50 border border-accent-200' : 'hover:bg-gray-50' }}">
                <span class="w-8 text-center text-lg font-bold {{ $rank === 1 ? 'text-accent-600' : ($rank <= 3 ? 'text-accent-500' : 'text-content-muted') }}">
                    {{ $rank <= 3 ? ['🥇','🥈','🥉'][$rank - 1] : $rank }}
                </span>
                <div class="avatar-primary text-xs overflow-hidden select-none shrink-0 flex items-center justify-center">
                    @if($u->avatar)
                        <img src="{{ asset('storage/' . $u->avatar) }}" alt="{{ $u->name }}'s Avatar" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($u->name, 0, 2)) }}
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-content">{{ $u->name }}</p>
                    <p class="text-xs text-content-muted">{{ $u->journey_title }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-primary">{{ number_format($u->xp) }} XP</p>
                    <p class="text-xs text-content-muted">Level {{ $u->level }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
