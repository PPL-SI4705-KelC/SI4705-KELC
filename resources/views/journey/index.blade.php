<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Your Eco Journey - {{ config('app.name', 'Act4Climate') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Hide scrollbar for clean UI */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-[#fafbfc]">

    {{-- Top Navigation Bar --}}
    <header class="bg-white sticky top-0 z-50 border-b border-gray-200">
        <div class="w-full px-6 md:px-10 h-16 flex items-center gap-8">
            <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                <span class="font-extrabold text-[#1B4332] tracking-tight text-[22px]">Act4Climate</span>
            </div>
        </div>
    </header>

    <main class="py-10 px-6">
        @php
            $currentLevelXpStart = ($user->level - 1) * 1000;
            $nextLevelXp = $user->level * 1000;
            $xpInCurrentLevel = $user->xp - $currentLevelXpStart;
            $nextLevelName = $user->level < 6 ? \App\Services\GamificationService::getJourneyTitle($user->level + 1) : 'Max Level';
        @endphp

        <div class="w-full max-w-4xl mx-auto space-y-10">

            {{-- Hero Banner --}}
            <div class="relative bg-[#2A5C4D] rounded-[16px] p-8 text-white overflow-hidden shadow-sm">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Your Eco Journey</h1>
                        <p class="text-white/80 text-sm">Keep going! You're making a real difference for our planet.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-white/10 rounded-xl px-5 py-3 border border-white/5 min-w-[110px]">
                            <p class="text-[10px] text-white/70 font-bold uppercase tracking-wider mb-1">Current Level</p>
                            <p class="text-xl font-bold">Level {{ $user->level }}</p>
                        </div>
                        <div class="bg-white/10 rounded-xl px-5 py-3 border border-white/5 min-w-[110px]">
                            <p class="text-[10px] text-white/70 font-bold uppercase tracking-wider mb-1">Total Points</p>
                            <p class="text-xl font-bold">{{ number_format($user->xp) }}</p>
                        </div>
                        <div class="bg-white/10 rounded-xl px-5 py-3 border border-white/5 min-w-[110px]">
                            <p class="text-[10px] text-white/70 font-bold uppercase tracking-wider mb-1">CO₂ Saved</p>
                            <p class="text-xl font-bold">{{ number_format($totalCo2Saved, 0) }}kg</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress to Next Level --}}
            <div>
                <div class="flex items-end justify-between mb-3">
                    <div>
                        <h2 class="text-lg font-bold text-[#2A5C4D] tracking-tight">Progress to Next Level</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium">{{ number_format($xpToNext) }} points to reach {{ $nextLevelName }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-[#2A5C4D] leading-none">{{ round($levelProgress) }}%</span>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">COMPLETE</p>
                    </div>
                </div>
                <div class="relative w-full bg-gray-200 rounded-full h-2.5 overflow-visible flex items-center mt-3">
                    <div class="absolute inset-y-0 left-0 bg-[#2A5C4D] rounded-full transition-all duration-1000 ease-out" style="width: {{ min($levelProgress, 100) }}%"></div>
                    <div class="absolute w-5 h-5 bg-[#2A5C4D] rounded-full shadow-sm flex items-center justify-center transition-all duration-1000 ease-out z-10" style="left: calc({{ min($levelProgress, 100) }}% - 10px)">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Journey Map --}}
            <div>
                <h2 class="text-lg font-bold text-[#2A5C4D] tracking-tight mb-6">Your Journey Map</h2>

                <div class="relative space-y-3">
                    @foreach($journeyMap as $index => $milestone)
                    @php
                        $isCompleted = $user->level > $milestone['level'];
                        $isCurrent = $user->level == $milestone['level'];
                        $isLocked = $user->level < $milestone['level'];

                        $cardBg = $isCompleted ? 'bg-[#f8fcf9]' : ($isCurrent ? 'bg-white' : 'bg-gray-50/30');
                        $cardBorder = $isCompleted ? 'border-[#e2f0e7]' : ($isCurrent ? 'border-[#2A5C4D] shadow-sm' : 'border-gray-100');
                    @endphp

                    <div class="relative group transition-all duration-300 {{ $isCurrent ? 'scale-[1.01]' : 'hover:scale-[1.005]' }}">
                        <div class="absolute -inset-1.5 bg-gradient-to-r from-[#2A5C4D]/10 to-[#4CAF50]/10 rounded-2xl blur-sm opacity-0 {{ $isCurrent ? 'opacity-100' : 'group-hover:opacity-100' }} transition-opacity"></div>
                        
                        <div class="relative rounded-[16px] border {{ $cardBorder }} {{ $cardBg }} p-6 {{ $isLocked ? 'opacity-70' : '' }}">
                            <div class="flex items-start justify-between gap-6">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <h3 class="text-base font-bold {{ $isLocked ? 'text-gray-400' : 'text-[#2A5C4D]' }}">Level {{ $milestone['level'] }}: {{ $milestone['title'] }}</h3>
                                        @if($isCompleted)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#2A5C4D] text-white tracking-wide uppercase">Completed</span>
                                        @elseif($isCurrent)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white text-gray-500 border border-gray-200 tracking-wide uppercase shadow-sm">In Progress</span>
                                        @elseif($isLocked)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-400 border border-gray-200 tracking-wide uppercase">Locked</span>
                                        @endif
                                    </div>

                                    @if($isCompleted)
                                        <p class="text-xs text-gray-500 mt-1 font-semibold">Completed</p>
                                    @elseif($isCurrent)
                                        <p class="text-xs text-gray-900 mt-1 font-bold">Current Level - In Progress</p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1 font-medium">Locked - Requires {{ number_format($milestone['xp_required']) }} points</p>
                                    @endif

                                    <p class="text-sm text-gray-600 mt-3 leading-relaxed max-w-2xl">{{ $milestone['description'] }}</p>

                                    @if($isCurrent)
                                    <div class="mt-5 w-full">
                                        <div class="flex justify-between items-end mb-2">
                                            <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Progress</span>
                                            <span class="text-xs text-[#2A5C4D] font-bold">{{ number_format($user->xp) }} / {{ number_format($nextLevelXp) }} points</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-[#2A5C4D] h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ min($levelProgress, 100) }}%"></div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Tags --}}
                                    <div class="flex flex-wrap gap-x-4 gap-y-2 mt-5">
                                        @foreach($milestone['tags'] as $tag)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 {{ $isLocked ? 'text-gray-300' : 'text-[#2A5C4D]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span class="text-xs font-semibold {{ $isLocked ? 'text-gray-400' : 'text-gray-600' }}">{{ $tag }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Status Icon (Right Side) --}}
                                <div class="shrink-0 flex items-center justify-center pt-1">
                                    @if($isCompleted)
                                        <div class="w-8 h-8 rounded-full bg-[#2A5C4D] flex items-center justify-center shadow-sm">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    @elseif($isCurrent)
                                        <div class="w-8 h-8 rounded-full bg-[#2A5C4D] flex items-center justify-center shadow-sm">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Achievements --}}
            <div class="pt-6 pb-10">
                <h2 class="text-[14px] font-bold text-[#2A5C4D] tracking-tight mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    Recent Achievements
                </h2>

                <div class="space-y-2">
                    @forelse($xpHistory as $log)
                    @php
                        // Color mapping based on source
                        $sourceConfig = [
                            'quiz' => ['bg' => 'bg-[#fff4e5]', 'text' => 'text-[#ed8936]', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                            'blog' => ['bg' => 'bg-[#e6fffa]', 'text' => 'text-[#38b2ac]', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                            'emission' => ['bg' => 'bg-[#ebf8ff]', 'text' => 'text-[#4299e1]', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'community' => ['bg' => 'bg-[#f0fff4]', 'text' => 'text-[#48bb78]', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z']
                        ];
                        
                        // Fallback icon
                        $defaultConfig = ['bg' => 'bg-[#f3f4f6]', 'text' => 'text-gray-500', 'icon' => 'M5 13l4 4L19 7'];
                        $config = $sourceConfig[$log->source] ?? $defaultConfig;
                    @endphp
                    
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $config['bg'] }} flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 {{ $config['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-gray-900">{{ $log->description ?? ucfirst($log->source) }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-[12px] font-bold text-[#38b2ac]">+{{ $log->xp_amount }}</span>
                    </div>
                    @empty
                    <div class="text-center py-8 bg-white rounded-xl border border-gray-100">
                        <h3 class="text-[13px] font-bold text-gray-900 mb-1">No achievements yet</h3>
                        <p class="text-[11px] text-gray-500">Start earning XP by taking quizzes, tracking your carbon footprint, or writing blog posts!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
</html>
