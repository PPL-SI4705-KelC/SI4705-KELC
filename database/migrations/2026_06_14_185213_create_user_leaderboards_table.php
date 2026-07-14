<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('total_xp')->default(0);
            $table->integer('monthly_xp')->default(0)->index();
            $table->timestamps();
        });

        // Initialize leaderboard records for existing users using their current XP
        $existingUsers = DB::table('users')->select('id', 'xp')->get();
        $records = [];
        $now = now();

        foreach ($existingUsers as $user) {
            $records[] = [
                'user_id' => $user->id,
                'total_xp' => $user->xp,
                'monthly_xp' => $user->xp,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($records)) {
            DB::table('user_leaderboards')->insert($records);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_leaderboards');
    }
};
