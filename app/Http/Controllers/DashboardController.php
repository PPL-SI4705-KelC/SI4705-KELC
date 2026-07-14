<?php

namespace App\Http\Controllers;

use App\Models\Emission;
use App\Models\Blog;
use App\Models\QuizAttempt;
use App\Services\GamificationService;
use App\Services\LeaderboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private GamificationService $gamificationService,
        private LeaderboardService $leaderboardService
    ) {}

    public function index()
    {
        $this->leaderboardService->syncMissing();
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

        // Ensure current user has leaderboard record
        if ($user && $user->role === 'user' && !$user->leaderboard) {
            $user->leaderboard()->create([
                'total_xp' => $user->xp ?? 0,
                'monthly_xp' => $user->xp ?? 0,
            ]);
            $user->load('leaderboard');
        }

        // Get all user IDs sorted by monthly XP desc, id asc to determine globally unique sequential ranks
        $sortedUserIds = \App\Models\User::where('users.role', 'user')
            ->join('user_leaderboards', 'users.id', '=', 'user_leaderboards.user_id')
            ->orderByDesc('user_leaderboards.monthly_xp')
            ->orderBy('users.id', 'asc')
            ->pluck('users.id')
            ->toArray();

        // Assign rank to the current user
        $user->rank = array_search($user->id, $sortedUserIds) !== false 
            ? array_search($user->id, $sortedUserIds) + 1 
            : 1;

        // Leaderboard by monthly XP (only users)
        $leaderboard = \App\Models\User::where('users.role', 'user')
            ->select('users.*')
            ->join('user_leaderboards', 'users.id', '=', 'user_leaderboards.user_id')
            ->orderByDesc('user_leaderboards.monthly_xp')
            ->orderBy('users.id', 'asc')
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
            $player->load('leaderboard');
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

    public function leaderboard(Request $request)
    {
        $this->leaderboardService->syncMissing();
        $user = Auth::user();
        $filter = $request->query('filter', 'monthly');

        // Ensure current user has leaderboard record
        if ($user && $user->role === 'user' && !$user->leaderboard) {
            $user->leaderboard()->create([
                'total_xp' => $user->xp ?? 0,
                'monthly_xp' => $user->xp ?? 0,
            ]);
            $user->load('leaderboard');
        }

        // Base query joining user_leaderboards
        $query = \App\Models\User::where('users.role', 'user')
            ->select('users.*')
            ->join('user_leaderboards', 'users.id', '=', 'user_leaderboards.user_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.username', 'like', "%{$search}%");
            });
        }

        // Sort based on filter
        if ($filter === 'alltime') {
            $query->orderByDesc('user_leaderboards.total_xp')
                  ->orderBy('users.id', 'asc');
        } else {
            // Default to monthly
            $query->orderByDesc('user_leaderboards.monthly_xp')
                  ->orderBy('users.id', 'asc');
        }

        $users = $query->paginate(20)->withQueryString();

        // Get all user IDs sorted in the same way to calculate rank
        $rankQuery = \App\Models\User::where('users.role', 'user')
            ->join('user_leaderboards', 'users.id', '=', 'user_leaderboards.user_id');
            
        if ($filter === 'alltime') {
            $rankQuery->orderByDesc('user_leaderboards.total_xp')
                      ->orderBy('users.id', 'asc');
        } else {
            $rankQuery->orderByDesc('user_leaderboards.monthly_xp')
                      ->orderBy('users.id', 'asc');
        }
        
        $sortedUserIds = $rankQuery->pluck('users.id')->toArray();

        // Attach global rank to each user in the paginated collection
        $users->through(function ($player) use ($sortedUserIds) {
            $player->rank = array_search($player->id, $sortedUserIds) !== false 
                ? array_search($player->id, $sortedUserIds) + 1 
                : 1;
            $player->load('leaderboard');
            return $player;
        });

        // Assign rank to the current user
        $user->rank = array_search($user->id, $sortedUserIds) !== false 
            ? array_search($user->id, $sortedUserIds) + 1 
            : 1;

        return view('leaderboard', compact('users', 'user', 'filter'));
    }
}
