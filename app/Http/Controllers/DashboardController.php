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

        $totalEmissions = $user->emissions()->sum('total_emission');
        $avgEmission = $user->emissions()->avg('total_emission') ?? 0;
        $totalActivities = $user->activities()->count();

        // Calculate Trend Data
        $current7Days = $user->emissions()
            ->where('emission_date', '>=', Carbon::now()->subDays(7))
            ->sum('total_emission');
        $previous7Days = $user->emissions()
            ->whereBetween('emission_date', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
            ->sum('total_emission');
            
        $trendPercentage = 0;
        $trendDirection = 'same';
        if ($previous7Days > 0) {
            $trendPercentage = (($current7Days - $previous7Days) / $previous7Days) * 100;
            $trendDirection = $trendPercentage > 0 ? 'up' : ($trendPercentage < 0 ? 'down' : 'same');
        } elseif ($current7Days > 0) {
            $trendPercentage = 100;
            $trendDirection = 'up';
        }
        
        $trendPercentage = abs(round($trendPercentage, 1));

        // SDG Score (average / accumulated)
        $sdgScore = $user->emissions()->count() > 0 
            ? round($user->emissions()->avg('sdg_score'), 1) 
            : 0;

        $transportEmission = $user->emissions()->sum('transport_emission');
        $energyEmission = $user->emissions()->sum('energy_emission');
        $foodEmission = $user->emissions()->sum('consumption_emission');

        // Get all user IDs sorted by XP desc, id asc to determine globally unique sequential ranks
        $sortedUserIds = \App\Models\User::where('role', 'user')
            ->orderByDesc('xp')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        // Assign rank to the current user
        $user->rank = array_search($user->id, $sortedUserIds) !== false 
            ? array_search($user->id, $sortedUserIds) + 1 
            : 1;

        // Leaderboard by XP (only users)
        $leaderboard = \App\Models\User::where('role', 'user')
            ->orderByDesc('xp')
            ->orderBy('id', 'asc')
            ->limit(8)
            ->get();

        if ($user->role === 'user' && !$leaderboard->contains('id', $user->id)) {
            $leaderboard->push($user);
        }

        // Assign rank to each player in the leaderboard
        foreach ($leaderboard as $player) {
            $player->rank = array_search($player->id, $sortedUserIds) !== false 
                ? array_search($player->id, $sortedUserIds) + 1 
                : 1;
        }

        // Sort leaderboard by rank to ensure sequential rendering order
        $leaderboard = $leaderboard->sortBy('rank')->values();

        // Gamification data
        $xpToNext = $this->gamificationService->xpToNextLevel($user);
        $levelProgress = $this->gamificationService->levelProgress($user);

        // Recent quiz
        $todayQuiz = QuizAttempt::where('user_id', $user->id)
            ->where('attempt_date', Carbon::today())
            ->first();

        // Blog stats
        $publishedBlogs = $user->blogs()->published()->count();
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
            'foodEmission',
            'trendPercentage',
            'trendDirection'
        ));
    }

    /**
     * User Leaderboard page.
     */
    public function leaderboard(Request $request)
    {
        $user = Auth::user();
        $query = \App\Models\User::where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('xp')
            ->orderBy('id', 'asc')
            ->paginate(20)
            ->withQueryString();

        // Get all user IDs sorted by XP desc, id asc to determine globally unique sequential ranks
        $sortedUserIds = \App\Models\User::where('role', 'user')
            ->orderByDesc('xp')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        // Attach global rank to each user in the paginated collection using the sorted array index
        $users->through(function ($player) use ($sortedUserIds) {
            $player->rank = array_search($player->id, $sortedUserIds) !== false 
                ? array_search($player->id, $sortedUserIds) + 1 
                : 1;
            return $player;
        });

        // Assign rank to the current user
        $user->rank = array_search($user->id, $sortedUserIds) !== false 
            ? array_search($user->id, $sortedUserIds) + 1 
            : 1;

        return view('leaderboard', compact('users', 'user'));
    }
}
