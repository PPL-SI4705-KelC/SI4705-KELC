<?php

use App\Models\User;
use App\Models\Community;
use App\Models\Post;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('user can delete their own post in a community', function () {
    // 1. Create a user
    $user = User::factory()->create([
        'name' => 'Community Member',
        'username' => 'member123',
        'email' => 'member123@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
    ]);

    // 2. Create an active community
    $community = Community::create([
        'name' => 'Zero Waste Movement',
        'slug' => 'zero-waste-movement',
        'description' => 'Discuss Zero Waste lifestyle, tips, and events.',
        'is_active' => true,
        'created_by' => $user->id,
    ]);

    // 3. Make user a member of the community
    $community->members()->attach($user->id, ['role' => 'member']);
    $community->increment('member_count');

    // 4. Create a post by this user
    $post = Post::create([
        'user_id' => $user->id,
        'community_id' => $community->id,
        'content' => 'This is a test post that will be deleted by Dusk.',
    ]);

    $this->browse(function (Browser $browser) use ($user, $community, $post) {
        $browser->loginAs($user)
            ->visit('/community/' . $community->id)
            ->waitForText($community->name)
            ->assertSee($post->content)
            
            // 5. Click the delete button inside the specific post container
            ->click('#post-' . $post->id . ' button[title="Delete post"]')
            
            // 6. Confirm deletion in the custom confirm modal
            ->waitForText('Are you absolutely sure?')
            ->press('Yes, delete')
            
            // 7. Wait for success notification and dismiss it
            ->waitForText('Post deleted.')
            ->press('OK')
            ->pause(500) // Wait for modal to transition out
            
            // 8. Verify post is removed from the feed
            ->assertDontSee($post->content);
    });

    // 9. Verify database entry is deleted
    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});
