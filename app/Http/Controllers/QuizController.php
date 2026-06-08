<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                ->where('attempt_date', now()->toDateString())
                ->first();

            return view('quiz.result', [
                'attempt' => $todayAttempt,
                'stats' => $this->quizService->getUserStats($user),
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
            'answers' => ['required', 'array'],
        ]);

        $attempt = $this->quizService->submitQuiz(
            $user,
            $request->input('question_ids'),
            $request->input('answers')
        );

        return redirect()->route('quiz.index')
            ->with('success', "Quiz completed! You scored {$attempt->correct_count}/3 and earned {$attempt->xp_earned} XP!");
    }
}
