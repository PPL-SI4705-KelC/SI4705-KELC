<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leaderboard_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('year_month', 7)->index();
            $table->integer('xp')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'year_month']);
        });

        // Initialize history records for existing user leaderboards for the current month
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $currentMonth = $now->format('Y-m');
        $leaderboards = \Illuminate\Support\Facades\DB::table('user_leaderboards')->get();
        $historyData = [];

        foreach ($leaderboards as $lb) {
            $historyData[] = [
                'user_id' => $lb->user_id,
                'year_month' => $currentMonth,
                'xp' => $lb->monthly_xp,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($historyData)) {
            \Illuminate\Support\Facades\DB::table('leaderboard_histories')->insert($historyData);
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_histories');
    }
};
