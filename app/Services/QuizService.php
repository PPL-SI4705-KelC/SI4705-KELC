<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Carbon\Carbon;

class QuizService
{
    private const QUESTIONS_PER_DAY = 3;
    private const XP_PER_CORRECT = 200;

    public function __construct(
        private GamificationService $gamificationService
    ) {}

    /**
     * Check if user has already taken quiz today.
     */
    public function hasAttemptedToday(User $user): bool
    {
        return QuizAttempt::where('user_id', $user->id)
            ->where('attempt_date', Carbon::today())
            ->exists();
    }

    /**
     * Get today's quiz questions (random selection).
     */
    public function getTodayQuestions(): array
    {
        $categories = ['transport', 'energy', 'consumption'];
        $questions = collect();

        foreach ($categories as $category) {
            $q = Quiz::active()
                ->where('category', $category)
                ->inRandomOrder()
                ->first();
            
            if ($q) {
                $questions->push($q);
            }
        }

        // Fill with others if we don't have exactly 3
        if ($questions->count() < self::QUESTIONS_PER_DAY) {
            $existingIds = $questions->pluck('id')->toArray();
            $more = Quiz::active()
                ->whereNotIn('id', $existingIds)
                ->inRandomOrder()
                ->limit(self::QUESTIONS_PER_DAY - $questions->count())
                ->get();
            $questions = $questions->merge($more);
        }

        return $questions->toArray();
    }

    /**
     * Submit and grade quiz answers.
     */
    public function submitQuiz(User $user, array $questionIds, array $answers): QuizAttempt
    {
        $questions = Quiz::whereIn('id', $questionIds)->get()->keyBy('id');
        $correctCount = 0;

        foreach ($questionIds as $index => $qId) {
            $question = $questions->get($qId);
            if ($question && isset($answers[$index]) && (int) $answers[$index] === $question->correct_answer) {
                $correctCount++;
            }
        }

        $xpEarned = $correctCount * self::XP_PER_CORRECT;

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'attempt_date' => Carbon::today(),
            'question_ids' => $questionIds,
            'answers' => $answers,
            'correct_count' => $correctCount,
            'xp_earned' => $xpEarned,
        ]);

        // Award XP
        if ($xpEarned > 0) {
            $this->gamificationService->awardXp(
                $user,
                $xpEarned,
                'quiz',
                "Scored {$correctCount}/" . self::QUESTIONS_PER_DAY . " on daily quiz"
            );
        }

        return $attempt;
    }

    /**
     * Get quiz statistics for a user.
     */
    public function getUserStats(User $user): array
    {
        $attempts = $user->quizAttempts();

        return [
            'total_attempts' => $attempts->count(),
            'total_correct' => $attempts->sum('correct_count'),
            'total_xp_earned' => $attempts->sum('xp_earned'),
            'average_score' => round($attempts->avg('correct_count') ?? 0, 1),
            'streak' => $this->calculateStreak($user),
        ];
    }

    /**
     * Calculate consecutive days streak.
     */
    private function calculateStreak(User $user): int
    {
        $attempts = $user->quizAttempts()
            ->orderBy('attempt_date', 'desc')
            ->pluck('attempt_date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'));

        $streak = 0;
        $date = Carbon::today();

        foreach ($attempts as $attemptDate) {
            if ($attemptDate === $date->format('Y-m-d')) {
                $streak++;
                $date->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}
