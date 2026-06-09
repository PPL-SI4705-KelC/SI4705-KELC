<?php

use App\Models\User;
use App\Models\Community;
use App\Models\Post;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('user can update their own post in a community', function () {
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
        'content' => 'Original post content that will be updated.',
    ]);

    $this->browse(function (Browser $browser) use ($user, $community, $post) {
        $browser->loginAs($user)
            ->visit('/community/' . $community->id)
            ->waitForText($community->name)
            ->assertSee($post->content)
            
            // 5. Click the edit post button inside the specific post container
            ->click('#post-' . $post->id . ' button[title="Edit post"]')
            
            // 6. Wait for the edit textarea to appear and edit the content
            ->waitFor('#edit-post-content-' . $post->id)
            ->type('#edit-post-content-' . $post->id, 'This is the updated post content by Dusk.')
            ->press('Save')
            
            // 7. Wait for success notification and dismiss it
            ->waitForText('Post updated!')
            ->press('OK')
            ->pause(500) // Wait for modal to transition out
            
            // 8. Verify the updated content is displayed on the feed
            ->assertSee('This is the updated post content by Dusk.')
            ->assertDontSee('Original post content that will be updated.');
    });

    // 9. Verify database entry is updated
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'content' => 'This is the updated post content by Dusk.',
    ]);
});
