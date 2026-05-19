<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuizController extends Controller
{
    public function __construct(
        private QuizService $quizService
    ) {}

    /**
     * Show daily quiz.
     */
    public function index()
    {
        $user = Auth::user();

        if ($this->quizService->hasAttemptedToday($user)) {
            $todayAttempt = $user->quizAttempts()
                ->whereDate('attempt_date', now()->toDateString())
                ->latest()
                ->first();

            // Guard: jika attempt tidak ditemukan (edge case), tampilkan quiz
            if (!$todayAttempt) {
                $questions = $this->quizService->getTodayQuestions();
                return empty($questions)
                    ? view('quiz.empty')
                    : view('quiz.index', compact('questions'));
            }

            return view('quiz.result', [
                'attempt' => $todayAttempt,
                'stats'   => $this->quizService->getUserStats($user),
            ]);
        }

        $questions = $this->quizService->getTodayQuestions();

        if (empty($questions)) {
            return view('quiz.empty');
        }

        return view('quiz.index', compact('questions'));
    }

    /**
     * Submit quiz answers.
     */
    public function submit(Request $request)
    {
        $user = Auth::user();

        if ($this->quizService->hasAttemptedToday($user)) {
            return redirect()->route('quiz.index')
                ->with('error', 'You have already taken today\'s quiz.');
        }

        $request->validate([
            'question_ids' => ['required', 'array'],
            'answers'      => ['required', 'array'],
        ]);

        $attempt = $this->quizService->submitQuiz(
            $user,
            $request->input('question_ids'),
            $request->input('answers')
        );

        // Langsung render result dengan data attempt yang baru dibuat
        // (hindari re-query yang bisa gagal karena timezone / cache)
        return view('quiz.result', [
            'attempt' => $attempt,
            'stats'   => $this->quizService->getUserStats($user),
        ]);
    }

    /**
     * [DEV ONLY] Reset today's quiz attempt so you can re-take the quiz.
     * Only available when APP_ENV=local.
     */
    public function devResetToday()
    {
        abort_unless(app()->environment('local'), 403, 'Only available in local environment.');

        $user = Auth::user();

        $deleted = QuizAttempt::where('user_id', $user->id)
            ->where('attempt_date', Carbon::today())
            ->delete();

        return redirect()->route('quiz.index')
            ->with('success', $deleted
                ? '✅ [DEV] Today\'s quiz attempt has been reset. You can retake the quiz!'
                : '⚠️ [DEV] No attempt found for today.');
    }

    /**
     * [DEV ONLY] Simulate "tomorrow" by moving today's attempt to yesterday,
     * so you can test if the quiz is available again the next day.
     */
    public function devSimulateTomorrow()
    {
        abort_unless(app()->environment('local'), 403, 'Only available in local environment.');

        $user = Auth::user();

        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('attempt_date', Carbon::today())
            ->first();

        if ($attempt) {
            $attempt->update(['attempt_date' => Carbon::yesterday()]);
            return redirect()->route('quiz.index')
                ->with('success', '✅ [DEV] Quiz attempt moved to yesterday. Refresh to take today\'s quiz!');
        }

        return redirect()->route('quiz.index')
            ->with('info', '⚠️ [DEV] No attempt found for today to simulate.');
    }
}
