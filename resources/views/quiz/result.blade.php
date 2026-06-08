<x-app-layout>
    <x-slot name="title">Quiz Results</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Quiz Complete! 🎉</h1>
            <p class="text-sm text-content-muted">Today's quiz results</p>
        </div>
    </x-slot>

    <div class="max-w-lg mx-auto animate-slide-up" x-data="{ showResetAlert: true }">
        <!-- Modal Backdrop -->
        <div x-show="showResetAlert" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             style="display: none;">
            
            <!-- Modal Card -->
            <div x-show="showResetAlert"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white rounded-[32px] p-8 max-w-sm w-full shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-50 flex flex-col items-center text-center">
                 
                <!-- Alarm/Clock Icon inside premium badge -->
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mb-6 shadow-inner shrink-0">
                    <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                
                <h3 class="text-xl font-black text-gray-900 leading-tight">Daily Quiz Completed!</h3>
                <p class="text-sm text-gray-500 font-medium mt-3 leading-relaxed">
                    Kamu sudah menyelesaikan kuis hari ini. Kuis baru akan di-reset pada hari berikutnya pada pukul <span class="font-bold text-[#2A5C4D]">00:00 WIB</span>.
                </p>
                
                <button @click="showResetAlert = false" class="w-full mt-8 bg-[#2A5C4D] hover:bg-[#1e4237] text-white py-3.5 px-6 rounded-2xl font-bold text-sm transition shadow-sm active:scale-[0.98]">
                    Understood
                </button>
            </div>
        </div>

        <div class="card text-center">
            <div class="text-6xl mb-4">{{ $attempt->correct_count === 3 ? '🏆' : ($attempt->correct_count >= 2 ? '🌟' : '💪') }}</div>
            <p class="text-3xl font-bold text-content">{{ $attempt->correct_count }}/3</p>
            <p class="text-content-muted mt-1">Correct Answers</p>
            <div class="mt-4 inline-flex items-center gap-2 bg-accent-50 px-4 py-2 rounded-xl">
                <span class="text-accent-700 font-bold">+{{ $attempt->xp_earned }} XP</span>
            </div>
        </div>

        <div class="card mt-4">
            <h3 class="font-semibold text-content mb-3">Your Stats</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <p class="text-xl font-bold text-content">{{ $stats['total_attempts'] }}</p>
                    <p class="text-xs text-content-muted">Total Quizzes</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <p class="text-xl font-bold text-content">{{ $stats['streak'] }}</p>
                    <p class="text-xs text-content-muted">Day Streak 🔥</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <p class="text-xl font-bold text-content">{{ $stats['average_score'] }}</p>
                    <p class="text-xs text-content-muted">Avg Score</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <p class="text-xl font-bold text-content">{{ number_format($stats['total_xp_earned']) }}</p>
                    <p class="text-xs text-content-muted">Total XP</p>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('dashboard') }}" class="btn-outline">Back to Dashboard</a>
        </div>
    </div>
</x-app-layout>
