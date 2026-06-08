<x-app-layout>
    <x-slot name="title">Quiz Results</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Quiz Complete! 🎉</h1>
            <p class="text-sm text-content-muted">Today's quiz results</p>
        </div>
    </x-slot>

    <div class="max-w-lg mx-auto animate-slide-up">
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
