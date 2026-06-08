<?php

namespace App\Http\Controllers;

use App\Models\Emission;
use App\Models\Blog;
use App\Models\QuizAttempt;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private GamificationService $gamificationService
    ) {}

    public function index()
    {
        $user = Auth::user();

        // Latest emission
        $latestEmission = $user->emissions()->latest('emission_date')->first();

        // Weekly emissions for chart
        $weeklyEmissions = $user->emissions()
            ->where('emission_date', '>=', Carbon::now()->subDays(7))
            ->orderBy('emission_date')
            ->get();

        // Total stats
        $totalEmissions = $user->emissions()->sum('total_emission');
        $avgEmission = $user->emissions()->avg('total_emission') ?? 0;
        $totalActivities = $user->activities()->count();

        // SDG Score (latest)
        $sdgScore = $latestEmission?->sdg_score ?? 0;

        $transportEmission = $user->emissions()->sum('transport_emission');
        $energyEmission = $user->emissions()->sum('energy_emission');
        $foodEmission = $user->emissions()->sum('consumption_emission');

        // Leaderboard by XP
        $leaderboard = \App\Models\User::orderByDesc('xp')->limit(8)->get();
        
        // Ensure current user is in leaderboard or attach their rank
        $userRank = \App\Models\User::where('xp', '>', $user->xp)->count() + 1;
        $user->rank = $userRank;

        if (!$leaderboard->contains('id', $user->id)) {
            $leaderboard->push($user);
        } else {
            foreach($leaderboard as $idx => $u) {
                if($u->id === $user->id) {
                    $u->rank = $userRank;
                }
            }
        }

        // Gamification data
        $xpToNext = $this->gamificationService->xpToNextLevel($user);
        $levelProgress = $this->gamificationService->levelProgress($user);

        // Recent quiz
        $todayQuiz = QuizAttempt::where('user_id', $user->id)
            ->where('attempt_date', Carbon::today())
            ->first();

        // Blog stats
        $publishedBlogs = $user->blogs()->approved()->count();
        $pendingBlogs = $user->blogs()->pending()->count();

        return view('dashboard', compact(
            'user',
            'latestEmission',
            'weeklyEmissions',
            'totalEmissions',
            'avgEmission',
            'totalActivities',
            'sdgScore',
            'leaderboard',
            'xpToNext',
            'levelProgress',
            'todayQuiz',
            'publishedBlogs',
            'pendingBlogs',
            'transportEmission',
            'energyEmission',
            'foodEmission'
        ));
    }
}
