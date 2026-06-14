<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserLeaderboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Display the leaderboard list.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        app(\App\Services\LeaderboardService::class)->syncMissing();
        $filter = $request->query('filter', 'monthly');

        // Only fetch leaderboard for users with 'user' role
        $query = UserLeaderboard::whereHas('user', function ($q) {
            $q->where('role', 'user');
        })->with(['user:id,name,username,avatar']);

        if ($filter === 'alltime') {
            $query->orderByDesc('total_xp')->orderBy('user_id', 'asc');
        } else {
            // Default to monthly_xp
            $query->orderByDesc('monthly_xp')->orderBy('user_id', 'asc');
        }

        $leaderboard = $query->limit(50)->get();

        $formattedData = $leaderboard->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'user_id' => $item->user_id,
                'name' => $item->user?->name ?? 'Unknown User',
                'username' => $item->user?->username,
                'avatar' => $item->user?->avatar,
                'total_xp' => $item->total_xp,
                'monthly_xp' => $item->monthly_xp,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Leaderboard retrieved successfully',
            'filter' => $filter === 'alltime' ? 'alltime' : 'monthly',
            'data' => $formattedData,
        ]);
    }

    /**
     * Get a user's monthly XP history.
     */
    public function history(\App\Models\User $user): JsonResponse
    {
        // Ensure user leaderboards are up to date
        app(\App\Services\LeaderboardService::class)->syncMissing();

        // 1. Get start date (registration date) and current date in Asia/Jakarta timezone
        $start = \Carbon\Carbon::parse($user->created_at)->tz('Asia/Jakarta')->startOfMonth();
        $end = \Carbon\Carbon::now('Asia/Jakarta')->startOfMonth();

        // If start is in the future compared to end (e.g. clock shift), clamp it
        if ($start->greaterThan($end)) {
            $start = $end->copy();
        }

        // 2. Fetch all history records for this user to index them by year_month
        $historyMap = $user->leaderboardHistories()
            ->get()
            ->pluck('xp', 'year_month')
            ->toArray();

        // 3. Generate the continuous sequence of months from registration to current month
        $formatted = [];
        $index = 1;
        $current = $start->copy();

        while ($current->lessThanOrEqualTo($end)) {
            $ym = $current->format('Y-m');
            $monthName = $current->format('F Y');
            $xp = $historyMap[$ym] ?? 0;

            $formatted[] = [
                'index' => $index,
                'label' => "Month " . $index,
                'year_month' => $ym,
                'month_name' => $monthName,
                'xp' => $xp,
            ];

            $index++;
            $current->addMonth();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Monthly XP history retrieved successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'registered_at' => $user->created_at->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
            'data' => $formatted,
        ]);
    }
}

