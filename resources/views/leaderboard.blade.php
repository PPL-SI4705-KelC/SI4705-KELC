<x-app-layout>
    <x-slot name="title">Leaderboard</x-slot>
    
    <x-slot name="header">
        <div class="flex items-center justify-between w-full" x-data="{ showHelp: false }">
            <div>
                <h1 class="text-2xl font-black text-[#2D5A4C] tracking-tight flex items-center">
                    Global Leaderboard 
                    <button @click="showHelp = true" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#2D5A4C]/10 text-[#2D5A4C] hover:bg-[#2D5A4C]/20 text-xs font-extrabold tracking-wide transition ml-3 shadow-sm" title="Leaderboard Help Info">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                        </svg>
                        Learn More
                    </button>
                </h1>
                <p class="text-sm text-gray-500 font-medium mt-1">See how you rank against other climate advocates around the world.</p>
            </div>

            <!-- Help Modal -->
            <div x-show="showHelp" 
                 class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-0"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm" @click="showHelp = false"></div>
                
                <!-- Card -->
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl border border-gray-100 transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-md z-10 p-8"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="scale-95 opacity-0 -translate-y-4"
                     x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="scale-100 opacity-100 translate-y-0"
                     x-transition:leave-end="scale-95 opacity-0 -translate-y-4">
                    
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                        <h3 class="text-lg font-black text-[#2D5A4C] flex items-center gap-2">
                            <span>ℹ️</span> Leaderboard System
                        </h3>
                        <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600 font-bold text-lg focus:outline-none">
                            &times;
                        </button>
                    </div>
                    
                    <div class="space-y-4 text-sm text-gray-600 leading-relaxed">
                        <p>Welcome to the <strong>Act4Climate</strong> leaderboard system! To reward your sustainability efforts and keep the competition engaging, we feature two ranking categories:</p>
                        
                        <div class="bg-gradient-to-br from-[#2D5A4C]/5 to-[#2D5A4C]/10 rounded-2xl p-4 border border-[#2D5A4C]/10">
                            <h4 class="font-bold text-[#2D5A4C] text-xs uppercase tracking-wider flex items-center gap-1.5 mb-1.5">
                                📅 Monthly Ranking
                            </h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Your <strong>Monthly XP</strong> is automatically reset to <strong>0</strong> at <strong>00:00 (WIB) on the 1st of every month</strong>. This ensures a fair playing field, giving both new and veteran members a fresh opportunity to claim the top spot each month!</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-[#2D5A4C]/5 to-[#2D5A4C]/10 rounded-2xl p-4 border border-[#2D5A4C]/10">
                            <h4 class="font-bold text-[#2D5A4C] text-xs uppercase tracking-wider flex items-center gap-1.5 mb-1.5">
                                👑 Lifetime Ranking
                            </h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Your <strong>Total XP</strong> is permanent and accumulates continuously throughout your journey on Act4Climate. It represents your lifetime contribution to climate action since account creation.</p>
                        </div>

                        <div class="pt-2">
                            <h4 class="font-bold text-gray-900 mb-1 text-xs uppercase tracking-wider">How to Earn XP:</h4>
                            <ul class="list-disc list-inside space-y-1 text-xs text-gray-500">
                                <li>Log your daily carbon emissions.</li>
                                <li>Complete daily quizzes about climate change.</li>
                                <li>Engage actively in our community.</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="button" @click="showHelp = false" class="px-5 py-3 rounded-xl bg-[#2D5A4C] hover:bg-[#1e4237] text-white font-bold text-xs shadow-md transition">
                            Got It
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="w-full space-y-6 animate-fade-in" x-data="leaderboardHistory()">
        {{-- Current User Rank Stats --}}
        <div class="bg-[#f0f9f5] rounded-3xl p-6 border border-[#2D5A4C]/10 flex flex-col sm:flex-row items-center justify-between shadow-sm gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div class="w-14 h-14 rounded-2xl bg-[#2D5A4C] text-white flex items-center justify-center font-black text-xl shadow-md shrink-0">
                    #{{ $user->rank }}
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Your Standing</h4>
                    <p class="text-lg font-black text-[#2D5A4C] mt-0.5">{{ $user->name }}</p>
                    <p class="text-xs font-semibold text-gray-500 mt-0.5">{{ $user->journey_title }} · Level {{ $user->level }}</p>
                </div>
            </div>
            <div class="flex gap-8 text-right w-full sm:w-auto justify-between sm:justify-end">
                <div>
                    <p class="text-2xl font-black text-[#2D5A4C]">{{ number_format($user->leaderboard?->monthly_xp ?? 0) }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Monthly XP</p>
                </div>
                <div class="border-l border-gray-200 pl-8">
                    <p class="text-2xl font-black text-[#2D5A4C]">{{ number_format($user->leaderboard?->total_xp ?? 0) }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Total XP</p>
                </div>
            </div>
        </div>

        {{-- Leaderboard Table Card --}}
        <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-gray-100">
            <!-- Filter Dropdown (Custom Alpine.js) -->
            <div class="mb-6 flex items-center gap-3" x-data="{ open: false }">
                <span class="text-xs font-black text-gray-500 uppercase tracking-wider">Rank Category:</span>
                <div class="relative inline-block">
                    <!-- Dropdown Trigger Button -->
                    <button @click="open = !open" 
                            @click.outside="open = false"
                            class="inline-flex items-center gap-2 pl-4 pr-12 py-2.5 text-xs font-black rounded-xl border border-gray-200 bg-white text-[#2D5A4C] hover:border-[#2D5A4C] focus:outline-none focus:ring-2 focus:ring-[#2D5A4C]/20 transition-all cursor-pointer shadow-sm w-64 text-left relative">
                        <span class="truncate">
                            @if($filter === 'alltime')
                                Total
                            @else
                                Monthly
                            @endif
                        </span>
                        <!-- Custom Arrow Icon -->
                        <div class="absolute inset-y-0 right-0 flex items-center px-3.5 text-[#2D5A4C]">
                            <svg class="w-4 h-4 fill-current transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Dropdown Options List -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute z-30 mt-2 w-64 rounded-2xl bg-white shadow-xl border border-gray-100 focus:outline-none py-1.5 overflow-hidden" 
                         style="display: none;">
                        <a href="{{ route('leaderboard', ['filter' => 'monthly', 'search' => request('search')]) }}" 
                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold transition-colors whitespace-nowrap {{ $filter === 'monthly' ? 'bg-[#f0f9f5] text-[#2D5A4C]' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="shrink-0"></span> Monthly 
                        </a>
                        <a href="{{ route('leaderboard', ['filter' => 'alltime', 'search' => request('search')]) }}" 
                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold transition-colors whitespace-nowrap {{ $filter === 'alltime' ? 'bg-[#f0f9f5] text-[#2D5A4C]' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="shrink-0"></span> Total
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-6 font-bold text-center w-16">Rank</th>
                            <th class="py-4 px-6 font-bold">User</th>
                            <th class="py-4 px-6 font-bold">Title</th>
                            <th class="py-4 px-6 font-bold text-right">{{ $filter === 'alltime' ? 'Total XP' : 'Monthly XP' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $index => $player)
                        @php
                            // Calculate absolute rank based on pagination
                            $rank = $player->rank ?? ($users->firstItem() + $index);
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
                        <tr class="{{ $bgClass }} cursor-pointer hover:bg-[#2D5A4C]/5 transition-colors" 
                            title="Click to view monthly XP history"
                            @click="openHistory({{ $player->id }}, '{{ addslashes($player->name) }}', '{{ addslashes($player->username) }}', '{{ $player->avatar ? asset('storage/' . $player->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($player->name) . '&background=E2E8F0&color=2D5A4C' }}')">
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
                                    @if($player->avatar)
                                        <img src="{{ asset('storage/' . $player->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&background=E2E8F0&color=2D5A4C" alt="Avatar" class="w-full h-full object-cover">
                                    @endif
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
                                {{ number_format($filter === 'alltime' ? ($player->leaderboard?->total_xp ?? 0) : ($player->leaderboard?->monthly_xp ?? 0)) }} <span class="text-xs font-bold text-gray-400 ml-0.5">XP</span>
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
        
        <!-- History Modal -->
        <div x-show="showHistoryModal" 
             @keydown.escape.window="showHistoryModal = false"
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;"
             x-cloak>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm" @click="showHistoryModal = false"></div>
            
            <!-- Card -->
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl border border-gray-100 transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-md z-10 p-8"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 opacity-0 -translate-y-4"
                 x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 opacity-100 translate-y-0"
                 x-transition:leave-end="scale-95 opacity-0 -translate-y-4">
                
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-lg font-black text-[#2D5A4C] flex items-center gap-2">
                        <span>📈</span> Monthly XP Progression
                    </h3>
                    <button @click="showHistoryModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-lg focus:outline-none cursor-pointer">
                        &times;
                    </button>
                </div>

                <!-- User Info -->
                <div class="flex items-center gap-4 bg-gray-50/50 p-4 rounded-2xl border border-gray-100 mb-6">
                    <img :src="historyUser.avatar" class="w-12 h-12 rounded-full object-cover border border-white shadow-sm" alt="User Avatar" />
                    <div>
                        <h4 class="font-extrabold text-gray-900" x-text="historyUser.name"></h4>
                        <p class="text-xs text-gray-400" x-text="'@' + historyUser.username"></p>
                    </div>
                </div>
                
                <!-- Loading State -->
                <div x-show="historyLoading" class="py-12 flex flex-col items-center justify-center gap-3">
                    <svg class="animate-spin h-8 w-8 text-[#2D5A4C]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <p class="text-xs text-gray-400 font-bold tracking-wide animate-pulse">Loading history...</p>
                </div>

                <!-- Content State -->
                <div x-show="!historyLoading" class="space-y-5">
                    <!-- Empty State -->
                    <template x-if="historyData.length === 0">
                        <div class="py-12 text-center text-gray-400">
                            <p class="text-sm font-medium">No monthly XP records found for this user.</p>
                        </div>
                    </template>

                    <!-- List of History Records -->
                    <template x-if="historyData.length > 0">
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                            <template x-for="item in historyData" :key="item.year_month">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <span class="text-xs font-black text-[#2D5A4C] uppercase tracking-wider block" x-text="item.label"></span>
                                            <span class="text-xs font-semibold text-gray-400" x-text="item.month_name"></span>
                                        </div>
                                        <span class="text-sm font-black text-[#2D5A4C]" x-text="item.xp.toLocaleString() + ' XP'"></span>
                                    </div>
                                    <!-- Bar Chart Visual -->
                                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-[#2D5A4C]/50 to-[#2D5A4C] rounded-full transition-all duration-500 ease-out"
                                             :style="'width: ' + ((item.xp / maxXp) * 100) + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 flex justify-end">
                    <button type="button" @click="showHistoryModal = false" class="px-5 py-3 rounded-xl bg-[#2D5A4C] hover:bg-[#1e4237] text-white font-bold text-xs shadow-md transition cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function leaderboardHistory() {
            return {
                showHistoryModal: false,
                historyUser: { name: '', username: '', avatar: '' },
                historyData: [],
                historyLoading: false,
                maxXp: 0,
                
                openHistory(userId, userName, userUsername, userAvatar) {
                    this.historyUser = { 
                        name: userName, 
                        username: userUsername, 
                        avatar: userAvatar 
                    };
                    this.showHistoryModal = true;
                    this.historyLoading = true;
                    this.historyData = [];
                    this.maxXp = 0;
                    
                    fetch(`/api/v1/leaderboard/${userId}/history`)
                        .then(res => res.json())
                        .then(json => {
                            if (json.status === 'success') {
                                this.historyData = json.data;
                                this.maxXp = Math.max(...json.data.map(item => item.xp), 1);
                            }
                        })
                        .catch(err => console.error(err))
                        .finally(() => {
                            this.historyLoading = false;
                        });
                }
            };
        }
    </script>
</x-app-layout>
