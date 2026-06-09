<?php

use App\Models\User;
use App\Models\Quiz;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('user can earn points from daily quiz', function () {
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

    // 2. Create user
    $user = User::factory()->create([
        'name' => 'Dusk Quiz User',
        'username' => 'duskquizuser',
        'email' => 'duskquizuser@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
        'xp' => 0,
        'total_point' => 0,
    ]);

    $this->browse(function (Browser $browser) use ($user, $q1, $q2, $q3) {
        $browser->loginAs($user)
            ->visit('/quiz')
            // Wait for the first question to load
            ->waitForText($q1->question)
            // Select the option 'Kereta listrik (KRL)' - which is option index 2 (3rd button)
            ->click('.grid button:nth-of-type(3)')
            ->pause(500)
            ->click('.mt-8 button:nth-of-type(2)') // Click NEXT QUESTION
            
            // Wait for the second question to load
            ->waitForText($q2->question)
            // Select the option 'Energi surya (matahari)' - which is option index 2 (3rd button)
            ->click('.grid button:nth-of-type(3)')
            ->pause(500)
            ->click('.mt-8 button:nth-of-type(2)') // Click NEXT QUESTION
            
            // Wait for the third question to load
            ->waitForText($q3->question)
            // Select the option 'Merencanakan menu makan...' - which is option index 1 (2nd button)
            ->click('.grid button:nth-of-type(2)')
            ->pause(500)
            ->click('.mt-8 button:nth-of-type(2)') // Click SUBMIT QUIZ
            
            // Wait for result modal to appear
            ->waitForText('Daily Quiz Completed!')
            ->assertSee('3/3')
            ->assertSee('+600 XP')
            // Click "Understood" in the reset modal
            ->press('Understood')
            
            // Verify Stats cards on the page
            ->pause(500)
            ->assertSee('Your Stats')
            ->assertSee('Day Streak')
            ->assertSee('Avg Score')
            ->assertSee('600');
    });

    // 3. Verify in database that the user's XP was updated
    $user->refresh();
    expect($user->xp)->toBe(600);
});
