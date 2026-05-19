<x-app-layout>
    <x-slot name="title">Quiz Results</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Quiz Complete! 🎉</h1>
            <p class="text-sm text-content-muted">Today's quiz results</p>
        </div>
    </x-slot>

    <div class="max-w-lg mx-auto animate-slide-up">

        {{-- Score Card --}}
        @if($attempt)
        <div class="card text-center">
            <div class="text-6xl mb-4">{{ $attempt->correct_count === 3 ? '🏆' : ($attempt->correct_count >= 2 ? '🌟' : '💪') }}</div>
            <p class="text-3xl font-bold text-content">{{ $attempt->correct_count }}/3</p>
            <p class="text-content-muted mt-1">Correct Answers</p>
            <div class="mt-4 inline-flex items-center gap-2 bg-accent-50 px-4 py-2 rounded-xl">
                <span class="text-accent-700 font-bold">+{{ $attempt->xp_earned }} XP</span>
            </div>
        </div>
        @else
        <div class="card text-center">
            <div class="text-6xl mb-4">⚠️</div>
            <p class="text-content-muted">Tidak ada data attempt ditemukan.</p>
            <a href="{{ route('quiz.index') }}" class="btn-outline mt-4 inline-block">Coba Quiz</a>
        </div>
        @endif

        {{-- Level Progress Card --}}
        @php
            $user = auth()->user();
            $gamification = app(\App\Services\GamificationService::class);
            $xpToNext   = $gamification->xpToNextLevel($user);
            $progress   = $gamification->levelProgress($user);
            $levelTitle = \App\Services\GamificationService::getJourneyTitle($user->level);
            $journeyMap = \App\Services\GamificationService::getJourneyMap();
            $nextLevel  = collect($journeyMap)->firstWhere('level', $user->level + 1);
        @endphp
        <div class="card mt-4">
            <h3 class="font-semibold text-content mb-3">Your Level Progress</h3>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 rounded-full bg-accent-50 flex items-center justify-center text-2xl">
                    {{ collect($journeyMap)->firstWhere('level', $user->level)['icon'] ?? '🌱' }}
                </div>
                <div class="flex-1">
                    <p class="font-bold text-content">Level {{ $user->level }} &mdash; {{ $levelTitle }}</p>
                    <p class="text-xs text-content-muted">{{ number_format($user->xp) }} XP total</p>
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden mb-1">
                <div class="bg-accent-600 h-full rounded-full transition-all duration-700"
                     style="width: {{ min(100, $progress) }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-content-muted">
                <span>{{ $progress }}%</span>
                @if($nextLevel)
                    <span>{{ $xpToNext }} XP to Level {{ $nextLevel['level'] }} &mdash; {{ $nextLevel['title'] }}</span>
                @else
                    <span>🏆 Max Level Reached!</span>
                @endif
            </div>
        </div>

        {{-- Stats Card --}}
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

        {{-- DEV ONLY: Testing Tools --}}
        @if(app()->environment('local'))
        <div class="card mt-6 border-2 border-dashed border-orange-300 bg-orange-50">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-orange-500 font-bold text-sm">🛠 DEV TESTING TOOLS</span>
                <span class="text-xs text-orange-400">(hanya tampil di environment local)</span>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('quiz.dev.reset') }}"
                   class="text-center px-4 py-2 rounded-xl bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600 transition"
                   onclick="return confirm('Reset attempt hari ini? XP yang sudah didapat tidak dihapus.')">
                    🔄 Reset Attempt Hari Ini
                </a>
                <a href="{{ route('quiz.dev.tomorrow') }}"
                   class="text-center px-4 py-2 rounded-xl bg-blue-500 text-white text-sm font-semibold hover:bg-blue-600 transition"
                   onclick="return confirm('Simulasikan hari besok? Attempt hari ini akan dipindah ke kemarin.')">
                    ⏩ Simulasi Hari Besok
                </a>
            </div>
            <p class="text-xs text-orange-400 mt-2">
                • <strong>Reset</strong>: Hapus attempt hari ini agar bisa mengerjakan ulang (XP tetap)<br>
                • <strong>Simulasi Besok</strong>: Pindah attempt hari ini ke kemarin → quiz terbuka lagi
            </p>
        </div>
        @endif

    </div>
</x-app-layout>
