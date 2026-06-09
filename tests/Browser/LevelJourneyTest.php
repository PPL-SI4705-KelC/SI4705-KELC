<?php

use App\Models\User;
use App\Models\Quiz;
use App\Models\Blog;
use App\Services\BlogService;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('user can level up their journey by doing daily quiz and publishing blogs', function () {
    // 1. Seed quizzes
    $q1 = Quiz::create([
        'question' => 'Mode transportasi manakah yang paling sedikit menghasilkan emisi karbon per penumpang?',
        'options' => ['Pesawat terbang', 'Mobil pribadi', 'Kereta listrik (KRL)', 'Bus bermesin diesel'],
        'correct_answer' => 2, // 'Kereta listrik (KRL)'
        'category' => 'transport',
        'difficulty' => 'easy',
        'is_active' => true,
    ]);

    $q2 = Quiz::create([
        'question' => 'Manakah di bawah ini yang merupakan sumber energi terbarukan (renewable energy)?',
        'options' => ['Batu bara', 'Gas alam', 'Energi surya (matahari)', 'Minyak bumi'],
        'correct_answer' => 2, // 'Energi surya (matahari)'
        'category' => 'energy',
        'difficulty' => 'easy',
        'is_active' => true,
    ]);

    $q3 = Quiz::create([
        'question' => 'Bagaimana cara terbaik mengurangi "food waste" atau sampah makanan di rumah?',
        'options' => ['Membeli makanan dalam jumlah besar sekaligus', 'Merencanakan menu makan dan membeli bahan sesuai kebutuhan', 'Selalu menyisakan makanan di piring', 'Membuang makanan sisa ke tempat sampah tanpa memilahnya'],
        'correct_answer' => 1, // 'Merencanakan menu makan dan membeli bahan sesuai kebutuhan'
        'category' => 'consumption',
        'difficulty' => 'easy',
        'is_active' => true,
    ]);

    // 2. Create user (Level 1, 0 XP, Eco Beginner)
    $user = User::factory()->create([
        'name' => 'Eco Journey Player',
        'username' => 'journeyplayer',
        'email' => 'journeyplayer@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
        'xp' => 0,
        'level' => 1,
        'total_point' => 0,
    ]);

    $this->browse(function (Browser $browser) use ($user, $q1, $q2, $q3) {
        // Step A: Login and verify initial Level 1 on Journey Map
        $browser->loginAs($user)
            ->visit('/journey')
            ->waitForText('Your Eco Journey')
            ->assertSee('Level 1')
            ->assertSee('Eco Beginner')
            ->assertSee('1,000 points to reach Green Starter'); // XP needed for Level 2

        // Step B: Complete the Daily Quiz (earn 600 XP)
        $browser->visit('/quiz')
            ->waitForText($q1->question)
            ->click('.grid button:nth-of-type(3)') // Select correct answer for Q1
            ->pause(300)
            ->click('.mt-8 button:nth-of-type(2)') // NEXT QUESTION
            
            ->waitForText($q2->question)
            ->click('.grid button:nth-of-type(3)') // Select correct answer for Q2
            ->pause(300)
            ->click('.mt-8 button:nth-of-type(2)') // NEXT QUESTION
            
            ->waitForText($q3->question)
            ->click('.grid button:nth-of-type(2)') // Select correct answer for Q3
            ->pause(300)
            ->click('.mt-8 button:nth-of-type(2)') // SUBMIT QUIZ
            
            ->waitForText('Daily Quiz Completed!')
            ->press('Understood'); // Dismiss reset modal

        // Step C: Go to Journey Map and check 600 XP (Still Level 1)
        $browser->visit('/journey')
            ->waitForText('Your Eco Journey')
            ->assertSee('Level 1')
            ->assertSee('600') // Total points
            ->assertSee('400 points to reach Green Starter'); // 1000 - 600 = 400 XP remaining

        // Step D: Simulate publishing the first blog post using Eloquent (gives 1000 XP)
        $blog1 = Blog::create([
            'user_id' => $user->id,
            'title' => 'My First Eco Friendly Day',
            'slug' => 'my-first-eco-friendly-day',
            'short_description' => 'Living green is fantastic and easy to start.',
            'content' => 'Living green is fantastic and easy to start.',
            'category' => Blog::CATEGORY_TRANSPORTATION,
            'status' => Blog::STATUS_PUBLISHED,
        ]);
        $blogService = app(BlogService::class);
        $blogService->awardBlogXp($blog1); // Awards 1000 XP (total 1600 XP -> Level 2)

        // Step E: Refresh Journey Map and check Level 2 (Green Starter)
        $browser->refresh()
            ->waitForText('Your Eco Journey')
            ->assertSee('Level 2')
            ->assertSee('Green Starter')
            ->assertSee('1,600') // Total points
            ->assertSee('400 points to reach Eco Warrior'); // 2000 - 1600 = 400 XP remaining to Level 3

        // Step F: Simulate publishing the second blog post using Eloquent (gives 500 XP)
        $blog2 = Blog::create([
            'user_id' => $user->id,
            'title' => 'Reducing Plastic Waste at Home',
            'slug' => 'reducing-plastic-waste-at-home',
            'short_description' => 'Here are 5 easy ways to reduce plastic waste.',
            'content' => 'Here are 5 easy ways to reduce plastic waste.',
            'category' => Blog::CATEGORY_CONSUMPTION,
            'status' => Blog::STATUS_PUBLISHED,
        ]);
        $blogService->awardBlogXp($blog2); // Awards 500 XP (total 2100 XP -> Level 3)

        // Step G: Refresh Journey Map and check Level 3 (Eco Warrior)
        $browser->refresh()
            ->waitForText('Your Eco Journey')
            ->assertSee('Level 3')
            ->assertSee('Eco Warrior')
            ->assertSee('2,100') // Total points
            ->assertSee('900 points to reach Climate Advocate'); // 3000 - 2100 = 900 XP remaining to Level 4
    });
});
