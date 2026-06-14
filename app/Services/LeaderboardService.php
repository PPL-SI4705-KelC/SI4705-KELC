<?php

namespace App\Services;

use App\Models\UserLeaderboard;
use Illuminate\Support\Facades\DB;

class LeaderboardService
{
    /**
     * Synchronize and create missing leaderboard records for users.
     */
    public function syncMissing(): void
    {
        // 0. Passive Monthly Reset Check (automatically resets monthly_xp if the calendar month changes)
        $currentMonth = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m');
        $lastReset = \Illuminate\Support\Facades\Cache::get('leaderboard_last_reset_month');

        if (!$lastReset) {
            $latestUpdate = UserLeaderboard::latest('updated_at')->value('updated_at');
            $shouldReset = false;
            if ($latestUpdate) {
                $latestUpdateMonth = \Carbon\Carbon::parse($latestUpdate)->tz('Asia/Jakarta')->format('Y-m');
                if ($latestUpdateMonth !== $currentMonth) {
                    $shouldReset = true;
                }
            }
            if ($shouldReset) {
                UserLeaderboard::query()->update([
                    'monthly_xp' => 0,
                    'updated_at' => now(),
                ]);
            }
            \Illuminate\Support\Facades\Cache::forever('leaderboard_last_reset_month', $currentMonth);
        } elseif ($lastReset !== $currentMonth) {
            UserLeaderboard::query()->update([
                'monthly_xp' => 0,
                'updated_at' => now(),
            ]);
            \Illuminate\Support\Facades\Cache::forever('leaderboard_last_reset_month', $currentMonth);
        }

        // 1. Insert missing user records in user_leaderboards
        $users = \App\Models\User::where('role', 'user')->get();
        foreach ($users as $user) {
            UserLeaderboard::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'total_xp' => $user->xp ?? 0,
                    'monthly_xp' => $user->xp ?? 0,
                ]
            );
        }

        // 2. Synchronize and self-heal user histories & leaderboards
        foreach ($users as $user) {
            $totalXp = $user->xp ?? 0;
            
            // Get registration month and current month
            $registrationDate = $user->created_at ? \Carbon\Carbon::parse($user->created_at) : \Carbon\Carbon::now();
            $start = $registrationDate->copy()->tz('Asia/Jakarta')->startOfMonth();
            $end = \Carbon\Carbon::now('Asia/Jakarta')->startOfMonth();
            
            if ($start->greaterThan($end)) {
                $start = $end->copy();
            }
            
            $regYm = $start->format('Y-m');
            $currYm = $end->format('Y-m');

            // Fetch existing history records for this user
            $existingHistories = DB::table('leaderboard_histories')
                ->where('user_id', $user->id)
                ->get()
                ->pluck('xp', 'year_month')
                ->toArray();

            $historySum = array_sum($existingHistories);

            if ($historySum !== $totalXp) {
                // Out of sync - rebuild histories from xp_logs and users.xp
                $logs = DB::table('xp_logs')
                    ->where('user_id', $user->id)
                    ->get(['xp_amount', 'created_at']);
                    
                $monthlyLogs = [];
                foreach ($logs as $log) {
                    $ym = \Carbon\Carbon::parse($log->created_at)->tz('Asia/Jakarta')->format('Y-m');
                    $monthlyLogs[$ym] = ($monthlyLogs[$ym] ?? 0) + $log->xp_amount;
                }
                
                $totalLoggedXp = array_sum($monthlyLogs);
                $unloggedXp = max(0, $totalXp - $totalLoggedXp);

                $current = $start->copy();
                while ($current->lessThanOrEqualTo($end)) {
                    $ym = $current->format('Y-m');
                    $xp = $monthlyLogs[$ym] ?? 0;
                    if ($ym === $regYm) {
                        $xp += $unloggedXp;
                    }
                    
                    DB::table('leaderboard_histories')->updateOrInsert(
                        ['user_id' => $user->id, 'year_month' => $ym],
                        [
                            'xp' => $xp,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                    
                    $current->addMonth();
                }
            } else {
                // In sync - only fill missing months (gaps) with 0
                $current = $start->copy();
                while ($current->lessThanOrEqualTo($end)) {
                    $ym = $current->format('Y-m');
                    if (!isset($existingHistories[$ym])) {
                        DB::table('leaderboard_histories')->insert([
                            'user_id' => $user->id,
                            'year_month' => $ym,
                            'xp' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    $current->addMonth();
                }
            }

            // Sync user_leaderboards with final values
            $currentMonthXp = DB::table('leaderboard_histories')
                ->where('user_id', $user->id)
                ->where('year_month', $currYm)
                ->value('xp') ?? 0;

            UserLeaderboard::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'total_xp' => $totalXp,
                    'monthly_xp' => $currentMonthXp,
                    'updated_at' => now(),
                ]
            );
        }
    }
    /**
     * Add reward XP to a user's leaderboard record.
     *
     * @param mixed $userId
     * @param int $xpGained
     * @return UserLeaderboard
     */
    public function addRewardXP($userId, int $xpGained): UserLeaderboard
    {
        return DB::transaction(function () use ($userId, $xpGained) {
            // Retrieve or create the leaderboard record to ensure it exists
            $leaderboard = UserLeaderboard::firstOrCreate(
                ['user_id' => $userId],
                ['total_xp' => 0, 'monthly_xp' => 0]
            );

            // Fetch with lock for update to ensure safe concurrent operations (race condition safety)
            $lockedLeaderboard = UserLeaderboard::where('id', $leaderboard->id)
                ->lockForUpdate()
                ->first();

            $lockedLeaderboard->total_xp += $xpGained;
            $lockedLeaderboard->monthly_xp += $xpGained;
            $lockedLeaderboard->save();

            // Sync with users table as well to prevent desync.
            // Using DB::table bypasses Eloquent events to prevent double-increment loops.
            DB::table('users')->where('id', $userId)->increment('xp', $xpGained);

            // Increment historical monthly record
            $currentMonth = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m');
            $history = \App\Models\LeaderboardHistory::firstOrCreate(
                ['user_id' => $userId, 'year_month' => $currentMonth],
                ['xp' => 0]
            );
            $history->increment('xp', $xpGained);

            // Trigger level up check for the User
            $user = \App\Models\User::find($userId);
            if ($user) {
                $gamification = app(GamificationService::class);
                $gamification->checkLevelUp($user);
            }

            return $lockedLeaderboard;
        });
    }
}
