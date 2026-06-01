<x-app-layout>
    <x-slot name="title">Global Leaderboard</x-slot>
    
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-black text-[#2D5A4C] tracking-tight">Global Leaderboard 🏆</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">See how you rank against other climate advocates around the world.</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
<<<<<<< HEAD
        {{-- Search and Current User Rank Widget --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Current User Rank Stats --}}
            <div class="md:col-span-2 bg-[#f0f9f5] rounded-3xl p-6 border border-[#2D5A4C]/10 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#2D5A4C] text-white flex items-center justify-center font-black text-xl shadow-md">
                        #{{ $user->rank }}
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Your Standing</h4>
                        <p class="text-lg font-black text-[#2D5A4C] mt-0.5">{{ $user->name }}</p>
                        <p class="text-xs font-semibold text-gray-500 mt-0.5">{{ $user->journey_title }} · Level {{ $user->level }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-black text-[#2D5A4C]">{{ number_format($user->xp) }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Total XP</p>
                </div>
            </div>

            {{-- Search Box --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-center">
                <form method="GET" action="{{ route('leaderboard') }}" class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." 
                           class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2D5A4C]/15 focus:border-[#2D5A4C] text-xs font-semibold shadow-inner transition">
                    <button type="submit" class="absolute right-3 top-3 text-[#2D5A4C] hover:text-[#1a382e]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
                @if(request()->filled('search'))
                    <a href="{{ route('leaderboard') }}" class="text-[10px] font-bold text-[#2D5A4C] hover:underline mt-2 text-center">Clear search filter</a>
                @endif
=======
        {{-- Current User Rank Stats --}}
        <div class="bg-[#f0f9f5] rounded-3xl p-6 border border-[#2D5A4C]/10 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#2D5A4C] text-white flex items-center justify-center font-black text-xl shadow-md">
                    #{{ $user->rank }}
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Your Standing</h4>
                    <p class="text-lg font-black text-[#2D5A4C] mt-0.5">{{ $user->name }}</p>
                    <p class="text-xs font-semibold text-gray-500 mt-0.5">{{ $user->journey_title }} · Level {{ $user->level }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-black text-[#2D5A4C]">{{ number_format($user->xp) }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Total XP</p>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
            </div>
        </div>

        {{-- Leaderboard Table Card --}}
        <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-6 font-bold text-center w-16">Rank</th>
                            <th class="py-4 px-6 font-bold">User</th>
                            <th class="py-4 px-6 font-bold">Title</th>
                            <th class="py-4 px-6 font-bold text-right">Points</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $index => $player)
                        @php
                            // Calculate absolute rank based on pagination
                            $rank = $users->firstItem() + $index;
                            $isCurrentUser = $player->id === Auth::id();
                            
                            $bgClass = 'hover:bg-gray-50/50 transition-colors';
                            if ($rank === 1) $bgClass = 'bg-[#fffdf2] border border-[#fef3c7] hover:bg-[#fffbe6]';
                            if ($isCurrentUser && $rank !== 1) $bgClass = 'bg-[#f0f9f5] border border-[#e1f3ec]';
                            
                            $badgeClass = 'bg-gray-400';
                            if ($rank === 1) $badgeClass = 'bg-[#f59e0b] ring-4 ring-[#f59e0b]/20';
                            elseif ($rank === 2) $badgeClass = 'bg-[#9ca3af]';
                            elseif ($rank === 3) $badgeClass = 'bg-[#d97706]';
                            if ($isCurrentUser && $rank > 3) $badgeClass = 'bg-[#2D5A4C]';
                        @endphp
                        <tr class="{{ $bgClass }}">
                            {{-- Rank column --}}
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $badgeClass }} text-white font-bold text-xs shrink-0 shadow-sm">
                                    {{ $rank }}
                                </div>
                            </td>
                            {{-- User Details column --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border-2 border-white shadow-sm shrink-0">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&background=E2E8F0&color=2D5A4C" alt="Avatar" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 flex items-center gap-1.5">
                                            {{ $player->name }}
                                            @if($isCurrentUser)
                                                <span class="bg-[#2D5A4C]/10 text-[#2D5A4C] text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">You</span>
                                            @endif
                                            @if($rank === 1)
                                                <span>👑</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium block">@.{{ $player->username }}</span>
                                    </div>
                                </div>
                            </td>
                            {{-- Level and Journey Title --}}
                            <td class="py-4 px-6">
                                <span class="font-semibold text-gray-700 text-xs block">{{ $player->journey_title }}</span>
                                <span class="text-[10px] text-gray-400 font-medium block">Level {{ $player->level }}</span>
                            </td>
                            {{-- XP score --}}
                            <td class="py-4 px-6 text-right font-extrabold text-[#2D5A4C] text-base">
                                {{ number_format($player->xp) }} <span class="text-xs font-bold text-gray-400 ml-0.5">XP</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400">
                                <p class="text-sm font-medium">No users found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($users->hasPages())
            <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6">
                <span class="text-xs text-gray-500 font-medium">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} climate champions
                </span>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
