<?php

use App\Models\User;
use App\Models\Community;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('user can join a community and publish a post', function () {
    // 1. Create a user
    $user = User::factory()->create([
        'name' => 'Community Member',
        'username' => 'member123',
        'email' => 'member123@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
    ]);

    // 2. Create an active community created by this user
    $community = Community::create([
        'name' => 'Zero Waste Movement',
        'slug' => 'zero-waste-movement',
        'description' => 'Discuss Zero Waste lifestyle, tips, and events.',
        'is_active' => true,
        'created_by' => $user->id,
    ]);

    $this->browse(function (Browser $browser) use ($user, $community) {
        $browser->loginAs($user)
            ->visit('/community/' . $community->id)
            ->waitForText($community->name)
            
            // 3. Join the community
            ->press('Join Community')
            ->waitForText('Leave Community') // Confirm user is now joined
            
            // 4. Dismiss success alert modal
            ->press('OK')
            ->pause(500) // Wait for backdrop to transition out
            
            // 5. Create a post
            ->type('#post-content-input', 'Hello eco warriors! Let us start our zero waste lifestyle today.')
            ->pause(500)
            ->press('Posting')
            
            // 6. Dismiss the "Post created!" success alert modal
            ->waitForText('Post created!')
            ->press('OK')
            ->pause(500)
            
            // 7. Verify the post is displayed on the feed
            ->waitForText('Hello eco warriors! Let us start our zero waste lifestyle today.')
            ->assertSee('@' . $user->username);
    });

    // 8. Verify database entry
    $this->assertDatabaseHas('posts', [
        'user_id' => $user->id,
        'community_id' => $community->id,
        'content' => 'Hello eco warriors! Let us start our zero waste lifestyle today.',
    ]);
});
