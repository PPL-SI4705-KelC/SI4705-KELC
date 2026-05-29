<x-app-layout>
    <div class="w-full space-y-6" x-data="{ showLearnMoreModal: false }">
        
        {{-- Hero Section --}}
        <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex flex-col md:flex-row items-center gap-10 relative overflow-hidden">
            <div class="flex-1 space-y-5">
                <h2 class="text-[32px] font-bold text-gray-900 tracking-tight">Climate Impact Calculator</h2>
                <p class="text-gray-500 leading-relaxed text-sm max-w-md">
                    Take our comprehensive climate assessment to calculate your carbon footprint and discover personalized actions to reduce your environmental impact. Join thousands of others in the fight against climate change.
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <a href="{{ route('calculator.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#2A5C4D] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#1e4237] transition text-sm min-w-[200px]">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                        Take the Questionnaire
                    </a>
                    <button @click="showLearnMoreModal = true" class="inline-flex items-center justify-center gap-2 bg-white text-gray-700 border border-gray-200 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition text-sm min-w-[140px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Learn More
                    </button>
                </div>
                <div class="flex items-center gap-1.5 text-[11px] text-gray-400 mt-4 font-medium uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Estimated time: 10-15 minutes</span>
                </div>
            </div>
            <div class="w-full md:w-[380px] h-[280px] rounded-2xl overflow-hidden shadow-sm shrink-0">
                {{-- Mockup shows a split nature/pollution image --}}
                <img src="https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&q=80&w=800" alt="Climate Impact" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Big Carbon Footprint --}}
            <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden flex flex-col justify-center min-h-[300px]">
                <div class="flex items-center gap-2 mb-8">
                    <svg class="w-5 h-5 text-[#2A5C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-xs font-bold tracking-[0.1em] text-gray-500 uppercase">CARBON FOOTPRINT</span>
                </div>
                <div class="flex items-baseline gap-3 z-10">
                    <span class="text-[80px] font-black text-[#2A5C4D] leading-none tracking-tight">{{ number_format($totalEmissions, 1) }}</span>
                    <span class="text-2xl font-bold text-gray-900">Kg</span>
                </div>
                <div class="flex items-center gap-1.5 mt-4 z-10">
                    <span class="{{ $trendDirection === 'down' ? 'text-emerald-600' : ($trendDirection === 'up' ? 'text-red-500' : 'text-gray-500') }} font-bold text-[15px]">
                        @if($trendDirection === 'down')
                            ↓ {{ $trendPercentage }}%
                        @elseif($trendDirection === 'up')
                            ↑ {{ $trendPercentage }}%
                        @else
                            {{ $trendPercentage }}%
                        @endif
                    </span>
                    <span class="text-gray-400 text-[15px] font-medium">vs previous 7 days</span>
                </div>
                
                <div class="mt-8 z-10 flex items-center gap-4 border-t border-gray-100 pt-6">
                    @php
                        $sdgColor = 'bg-red-500'; // default merah
                        if ($sdgScore >= 89) {
                            $sdgColor = 'bg-emerald-500'; // hijau 100-89
                        } elseif ($sdgScore >= 40) {
                            $sdgColor = 'bg-yellow-500'; // kuning 40-89
                        }
                    @endphp
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl shadow-sm text-white font-black text-xl {{ $sdgColor }}">
                        {{ $sdgScore }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 uppercase tracking-wider">SDG Impact Score</p>
                        <p class="text-xs text-gray-500">Your contribution to SDG 13 (Climate Action)</p>
                    </div>
                </div>
                
                {{-- Background Cloud Icon --}}
                <div class="absolute -bottom-12 -right-8 text-gray-50 opacity-80 z-0 pointer-events-none">
                    <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.9 11.2c-.3-4.3-3.9-7.7-8.3-7.7-3.8 0-7.1 2.5-8.1 6-.1 0-.2 0-.3 0-2.3 0-4.2 1.9-4.2 4.2 0 2.3 1.9 4.2 4.2 4.2h15.5c2.6 0 4.8-2.1 4.8-4.8 0-2.5-1.9-4.6-4.4-4.8zM17.7 16H3.2c-1.2 0-2.2-1-2.2-2.2 0-1.2 1-2.2 2.2-2.2.3 0 .7.1 1 .2l.5.2.2-.5c.8-2.6 3.1-4.4 5.9-4.4 3.4 0 6.2 2.7 6.4 6.1l.1.8.8.1c1.5.1 2.6 1.4 2.6 2.9 0 1.6-1.3 2.9-2.9 2.9z"/>
                        <path d="M10.2 13.8l-1.4-1.4-1.4 1.4 2.8 2.8 5.6-5.6-1.4-1.4z"/>
                    </svg>
                </div>
            </div>

            {{-- Breakdowns --}}
            <div class="space-y-4 flex flex-col justify-center">
                <div class="bg-[#f0f9f5] rounded-3xl p-5 flex items-center gap-5 border border-transparent hover:border-[#2A5C4D]/20 transition">
                    <div class="w-[52px] h-[52px] rounded-2xl bg-[#2A5C4D] flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-500 font-semibold mb-0.5">Transport</p>
                        <p class="text-base font-bold text-gray-900">{{ number_format($transportEmission, 1) }} kg CO₂</p>
                    </div>
                </div>
                <div class="bg-[#f0f9f5] rounded-3xl p-5 flex items-center gap-5 border border-transparent hover:border-[#2A5C4D]/20 transition">
                    <div class="w-[52px] h-[52px] rounded-2xl bg-[#2A5C4D] flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-500 font-semibold mb-0.5">Energy</p>
                        <p class="text-base font-bold text-gray-900">{{ number_format($energyEmission, 1) }} kg CO₂</p>
                    </div>
                </div>
                <div class="bg-[#f0f9f5] rounded-3xl p-5 flex items-center gap-5 border border-transparent hover:border-[#2A5C4D]/20 transition">
                    <div class="w-[52px] h-[52px] rounded-2xl bg-[#2A5C4D] flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-500 font-semibold mb-0.5">Food</p>
                        <p class="text-base font-bold text-gray-900">{{ number_format($foodEmission, 1) }} kg CO₂</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leaderboard --}}
        <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mt-2">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-[22px] font-bold text-gray-900 tracking-tight">Global Leaderboard</h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">Top climate champions ranked by experience points</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-[#D4AF37] uppercase tracking-wider">
                    <span>🏆</span> Updated daily
                </div>
            </div>
            
            <div class="space-y-3">
                @foreach($leaderboard as $index => $player)
                @php
                    $rank = $player->rank ?? ($index + 1);
                    $isCurrentUser = $player->id === Auth::id();
                    
                    $bgClass = 'bg-[#fafbfc] border border-gray-50 hover:bg-gray-50';
                    if ($rank === 1) $bgClass = 'bg-[#fffdf2] border border-[#fef3c7] hover:bg-[#fffbe6]';
                    if ($isCurrentUser && $rank !== 1) $bgClass = 'bg-[#f0f9f5] border border-[#e1f3ec]';
                    
                    $badgeClass = 'bg-gray-400';
                    if ($rank === 1) $badgeClass = 'bg-[#f59e0b] ring-4 ring-[#f59e0b]/20';
                    elseif ($rank === 2) $badgeClass = 'bg-[#9ca3af]';
                    elseif ($rank === 3) $badgeClass = 'bg-[#d97706]';
                    if ($isCurrentUser && $rank > 3) $badgeClass = 'bg-[#2A5C4D]';
                @endphp
                <div class="flex items-center justify-between p-4 px-6 rounded-2xl {{ $bgClass }} transition-colors cursor-pointer">
                    <div class="flex items-center gap-5">
                        <div class="w-[34px] h-[34px] rounded-full {{ $badgeClass }} text-white flex items-center justify-center font-bold text-[15px] shrink-0 shadow-sm">
                            {{ $rank }}
                        </div>
                        <div class="w-[46px] h-[46px] rounded-full bg-gray-200 overflow-hidden border-[3px] border-white shadow-sm shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&background=E2E8F0&color=475569" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-[15px] font-bold text-gray-900">{{ $isCurrentUser ? 'You' : $player->name }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-0.5">{{ $player->journey_title ?? 'Climate Champion' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[15px] font-bold text-gray-900">{{ number_format($player->xp) }} XP</p>
                        <p class="text-[11px] font-bold text-[#2A5C4D] mt-0.5">+{{ rand(50, 150) }} this week</p>
                    </div>
                    @if($rank === 1)
                    <div class="ml-4 text-xl shrink-0">👑</div>
                    @endif
                </div>
                @endforeach
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('leaderboard') }}" class="text-xs font-bold text-[#2A5C4D] hover:text-[#1e4237] uppercase tracking-widest transition-colors">View Full Leaderboard</a>
            </div>
        </div>

        {{-- Learn More Modal --}}
        <div x-show="showLearnMoreModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Background overlay -->
            <div x-show="showLearnMoreModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" 
                 @click="showLearnMoreModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="showLearnMoreModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                    
                    <!-- Close Button -->
                    <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block z-10">
                        <button type="button" @click="showLearnMoreModal = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D] focus:ring-offset-2">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white px-8 pb-8 pt-10 sm:p-10 sm:pb-10 relative">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-12 h-12 bg-[#f0f9f5] rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6 text-[#2A5C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <h3 class="text-2xl font-bold leading-6 text-gray-900" id="modal-title">How It Works</h3>
                                </div>
                                <div class="mt-6 space-y-6">
                                    <p class="text-sm text-gray-500 leading-relaxed">
                                        Our Climate Impact Calculator uses standardized emission factors to estimate your carbon footprint based on your daily activities. Here is a breakdown of how the calculation works:
                                    </p>
                                    
                                    <div class="grid gap-5">
                                        <!-- Transport -->
                                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                            <h4 class="text-base font-bold text-gray-900 mb-2 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-[#2A5C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                Transport Emissions
                                            </h4>
                                            <p class="text-sm text-gray-600 leading-relaxed">
                                                We calculate transport emissions by multiplying the distance you travel (in kilometers) by the average emission factor of your chosen vehicle type (e.g., car, motorcycle, public transit). EVs and walking have 0 emissions.
                                            </p>
                                        </div>
                                        
                                        <!-- Energy -->
                                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                            <h4 class="text-base font-bold text-gray-900 mb-2 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-[#2A5C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                                Energy Consumption
                                            </h4>
                                            <p class="text-sm text-gray-600 leading-relaxed">
                                                Your household electricity usage (in kWh) is multiplied by the regional grid emission factor. Reducing energy use or switching to renewable sources directly lowers this score.
                                            </p>
                                        </div>

                                        <!-- Food -->
                                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                            <h4 class="text-base font-bold text-gray-900 mb-2 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-[#2A5C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                                Diet & Food Waste
                                            </h4>
                                            <p class="text-sm text-gray-600 leading-relaxed">
                                                Different diets have different carbon intensities (e.g., meat-heavy vs. plant-based). We combine your dietary habits and food waste estimates to approximate your food-related emissions.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-8 py-5 sm:flex sm:flex-row-reverse sm:px-10 border-t border-gray-100">
                        <button type="button" @click="showLearnMoreModal = false" class="inline-flex w-full justify-center rounded-xl bg-[#2A5C4D] px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#1e4237] transition sm:ml-3 sm:w-auto">
                            Got it, thanks!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
