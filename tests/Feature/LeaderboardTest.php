<?php

use App\Models\User;
use App\Models\UserLeaderboard;
use App\Services\LeaderboardService;
use Illuminate\Support\Facades\Artisan;

test('user leaderboard migration and relationship work correctly', function () {
    $user = User::factory()->create();

    // Create a record manually to test relationship (cascade delete will also be tested)
    $leaderboard = UserLeaderboard::create([
        'user_id' => $user->id,
        'total_xp' => 100,
        'monthly_xp' => 50,
    ]);

    expect($user->fresh()->leaderboard)->not->toBeNull();
    expect($user->fresh()->leaderboard->total_xp)->toBe(100);
    expect($user->fresh()->leaderboard->monthly_xp)->toBe(50);
    expect($leaderboard->user->id)->toBe($user->id);
});

test('addRewardXP adds xp to both total_xp and monthly_xp inside transaction', function () {
    $user = User::factory()->create();

    $service = new LeaderboardService();

    // First reward when record might already exist (from factory/migration hooks) or doesn't exist
    $leaderboard = $service->addRewardXP($user->id, 50);

    // We fetch fresh values from the DB to be sure
    $dbRecord = UserLeaderboard::where('user_id', $user->id)->first();
    $initialTotal = $dbRecord->total_xp;
    $initialMonthly = $dbRecord->monthly_xp;

    // Second reward
    $leaderboard = $service->addRewardXP($user->id, 30);

    expect($leaderboard->total_xp)->toBe($initialTotal + 30);
    expect($leaderboard->monthly_xp)->toBe($initialMonthly + 30);
});

test('leaderboard api endpoint returns correct data structure and filter options', function () {
    // Create users (XP values reflect the history we seed below)
    $user1 = User::factory()->create(['name' => 'Alice', 'role' => 'user', 'xp' => 150]);
    $user2 = User::factory()->create(['name' => 'Bob', 'role' => 'user', 'xp' => 200]);
    $admin = User::factory()->create(['name' => 'Charlie', 'role' => 'admin', 'xp' => 500]);

    // Seed histories to keep them in sync with their user XP
    $currentMonth = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m');
    
    // Alice has 150 XP total and 150 XP this month
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user1->id,
        'year_month' => $currentMonth,
        'xp' => 150,
    ]);

    // Bob has 200 XP total: 150 XP from March 2026, 50 XP this month
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user2->id,
        'year_month' => '2026-03',
        'xp' => 150,
    ]);
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user2->id,
        'year_month' => $currentMonth,
        'xp' => 50,
    ]);

    // Admin (Charlie) has 500 XP total, 500 XP this month
    \App\Models\LeaderboardHistory::create([
        'user_id' => $admin->id,
        'year_month' => $currentMonth,
        'xp' => 500,
    ]);

    // Set explicit values in user_leaderboards to make sure they are in sync
    UserLeaderboard::updateOrCreate(['user_id' => $user1->id], ['monthly_xp' => 150, 'total_xp' => 150]);
    UserLeaderboard::updateOrCreate(['user_id' => $user2->id], ['monthly_xp' => 50, 'total_xp' => 200]);
    UserLeaderboard::updateOrCreate(['user_id' => $admin->id], ['monthly_xp' => 500, 'total_xp' => 500]);

    // 1. Default (monthly) filter
    $response = $this->getJson('/api/v1/leaderboard');
    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'filter',
            'data' => [
                '*' => ['rank', 'user_id', 'name', 'username', 'avatar', 'total_xp', 'monthly_xp']
            ]
        ]);

    $data = $response->json('data');
    
    // Default filter = monthly. user1 has monthly_xp = 150, user2 has monthly_xp = 50. So user1 should be first.
    // Admin (Charlie) should be excluded since they have role = 'admin'.
    expect($data)->toHaveCount(2);
    expect($data[0]['name'])->toBe('Alice');
    expect($data[0]['monthly_xp'])->toBe(150);
    expect($data[1]['name'])->toBe('Bob');
    expect($data[1]['monthly_xp'])->toBe(50);

    // 2. Alltime filter
    $response = $this->getJson('/api/v1/leaderboard?filter=alltime');
    $response->assertOk();
    $data = $response->json('data');
    
    // alltime filter. user2 has total_xp = 200, user1 has total_xp = 150. So user2 should be first.
    expect($data)->toHaveCount(2);
    expect($data[0]['name'])->toBe('Bob');
    expect($data[0]['total_xp'])->toBe(200);
    expect($data[1]['name'])->toBe('Alice');
    expect($data[1]['total_xp'])->toBe(150);
});

test('artisan command reset-monthly resets only monthly_xp to 0 for all users', function () {
    $user1 = User::factory()->create(['role' => 'user']);
    $user2 = User::factory()->create(['role' => 'user']);

    UserLeaderboard::updateOrCreate(['user_id' => $user1->id], ['monthly_xp' => 100, 'total_xp' => 150]);
    UserLeaderboard::updateOrCreate(['user_id' => $user2->id], ['monthly_xp' => 200, 'total_xp' => 250]);

    // Run command
    Artisan::call('leaderboard:reset-monthly');

    // Verify monthly_xp is reset but total_xp is intact
    $ul1 = UserLeaderboard::where('user_id', $user1->id)->first();
    expect($ul1->monthly_xp)->toBe(0);
    expect($ul1->total_xp)->toBe(150);

    $ul2 = UserLeaderboard::where('user_id', $user2->id)->first();
    expect($ul2->monthly_xp)->toBe(0);
    expect($ul2->total_xp)->toBe(250);
});

test('web leaderboard page returns a successful response and renders tabs', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/leaderboard');
    $response->assertOk();
    $response->assertSee('Global Leaderboard');
    $response->assertSee('Rank Category:');
});

test('passive monthly reset triggers when the calendar month shifts', function () {
    // Set user registration in May 2026 (XP matches May history = 150)
    $user = User::factory()->create(['role' => 'user', 'created_at' => '2026-05-15 12:00:00', 'xp' => 150]);
    
    // Seed May history manually
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user->id,
        'year_month' => '2026-05',
        'xp' => 150,
    ]);

    UserLeaderboard::updateOrCreate(['user_id' => $user->id], ['monthly_xp' => 100, 'total_xp' => 150]);

    // Set last reset month in cache to a past month
    \Illuminate\Support\Facades\Cache::forever('leaderboard_last_reset_month', '2026-05');

    // Set time to current month (e.g. 2026-06)
    \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2026-06-15 12:00:00', 'Asia/Jakarta'));

    // Trigger page sync
    app(LeaderboardService::class)->syncMissing();

    // Verify monthly_xp is reset to 0
    $ul = UserLeaderboard::where('user_id', $user->id)->first();
    expect($ul->monthly_xp)->toBe(0);
    expect($ul->total_xp)->toBe(150);

    // Verify cache is updated
    expect(\Illuminate\Support\Facades\Cache::get('leaderboard_last_reset_month'))->toBe('2026-06');

    // Clean up test time
    \Carbon\Carbon::setTestNow();
});

test('leaderboard history api returns chronological entries with formatted month index and names', function () {
    // Freeze time to June 2026
    \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2026-06-15 12:00:00', 'Asia/Jakarta'));

    // Set user registration in April 2026 (XP matches April 120 + May 300 = 420)
    $user = User::factory()->create(['role' => 'user', 'created_at' => '2026-04-15 12:00:00', 'xp' => 420]);
    
    // Seed some history entries manually
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user->id,
        'year_month' => '2026-04',
        'xp' => 120,
    ]);
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user->id,
        'year_month' => '2026-05',
        'xp' => 300,
    ]);

    // Request the history endpoint
    $response = $this->getJson("/api/v1/leaderboard/{$user->id}/history");
    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'user' => ['id', 'name', 'username', 'avatar', 'registered_at'],
            'data' => [
                '*' => ['index', 'label', 'year_month', 'month_name', 'xp']
            ]
        ]);

    $data = $response->json('data');
    expect($data)->toHaveCount(3);

    // Verify chronological ordering
    expect($data[0]['year_month'])->toBe('2026-04');
    expect($data[0]['label'])->toBe('Month 1');
    expect($data[0]['month_name'])->toBe('April 2026');
    expect($data[0]['xp'])->toBe(120);

    expect($data[1]['year_month'])->toBe('2026-05');
    expect($data[1]['label'])->toBe('Month 2');
    expect($data[1]['month_name'])->toBe('May 2026');
    expect($data[1]['xp'])->toBe(300);

    expect($data[2]['year_month'])->toBe('2026-06');
    expect($data[2]['label'])->toBe('Month 3');
    expect($data[2]['month_name'])->toBe('June 2026');
    expect($data[2]['xp'])->toBe(0);

    // Clean up test time
    \Carbon\Carbon::setTestNow();
});

test('leaderboard history api returns zero-filled months for gaps between registration and current date', function () {
    // Freeze time to June 2026
    \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2026-06-15 12:00:00', 'Asia/Jakarta'));

    // User registered in March 2026 with 1950 XP (March 450 + June 1500 = 1950)
    $user = User::factory()->create(['role' => 'user', 'created_at' => '2026-03-01 10:00:00', 'xp' => 1950]);
    UserLeaderboard::updateOrCreate(['user_id' => $user->id], ['monthly_xp' => 1500, 'total_xp' => 1950]);

    // Seed history only for March (Month 1) and June (Month 4)
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user->id,
        'year_month' => '2026-03',
        'xp' => 450,
    ]);
    \App\Models\LeaderboardHistory::create([
        'user_id' => $user->id,
        'year_month' => '2026-06',
        'xp' => 1500,
    ]);


    $response = $this->getJson("/api/v1/leaderboard/{$user->id}/history");
    $response->assertOk();

    $data = $response->json('data');
    expect($data)->toHaveCount(4); // March, April, May, June

    // Month 1 (March 2026) -> 450 XP
    expect($data[0]['year_month'])->toBe('2026-03');
    expect($data[0]['label'])->toBe('Month 1');
    expect($data[0]['xp'])->toBe(450);

    // Month 2 (April 2026) -> 0 XP (gap filled)
    expect($data[1]['year_month'])->toBe('2026-04');
    expect($data[1]['label'])->toBe('Month 2');
    expect($data[1]['xp'])->toBe(0);

    // Month 3 (May 2026) -> 0 XP (gap filled)
    expect($data[2]['year_month'])->toBe('2026-05');
    expect($data[2]['label'])->toBe('Month 3');
    expect($data[2]['xp'])->toBe(0);

    // Month 4 (June 2026) -> 1500 XP
    expect($data[3]['year_month'])->toBe('2026-06');
    expect($data[3]['label'])->toBe('Month 4');
    expect($data[3]['xp'])->toBe(1500);

    \Carbon\Carbon::setTestNow();
});






