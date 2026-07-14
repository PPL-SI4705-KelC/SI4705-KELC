<?php

namespace App\Console\Commands;

use App\Models\UserLeaderboard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetMonthlyLeaderboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaderboard:reset-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset monthly XP for all users to 0 at the beginning of each month';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting monthly leaderboard reset...');
        Log::info('Monthly leaderboard reset started.');

        try {
            // Update monthly_xp to 0 for all records. total_xp is NOT changed.
            $affected = UserLeaderboard::query()->update(['monthly_xp' => 0]);

            // Sync cache to prevent double-resetting in passive check
            $currentMonth = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m');
            \Illuminate\Support\Facades\Cache::forever('leaderboard_last_reset_month', $currentMonth);

            $this->info("Successfully reset monthly XP for {$affected} users.");
            Log::info("Monthly leaderboard reset completed. Affected rows: {$affected}.");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to reset monthly leaderboard: ' . $e->getMessage());
            Log::error('Monthly leaderboard reset failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
