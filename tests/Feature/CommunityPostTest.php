<?php

use App\Models\User;
use App\Models\Community;
use App\Models\Post;

test('post author can update their post content', function () {
    $user = User::factory()->create();
    
    $community = Community::create([
        'name' => 'Test Community',
        'slug' => 'test-community',
        'is_active' => true,
    ]);

    $post = Post::create([
        'user_id' => $user->id,
        'community_id' => $community->id,
        'content' => 'Original caption',
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('posts.update', $post), [
            'content' => 'Updated caption',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertEquals('Updated caption', $post->fresh()->content);
});

test('non-author cannot update a post content', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();

    $community = Community::create([
        'name' => 'Test Community',
        'slug' => 'test-community',
        'is_active' => true,
    ]);

    $post = Post::create([
        'user_id' => $author->id,
        'community_id' => $community->id,
        'content' => 'Original caption',
    ]);

    $response = $this
        ->actingAs($otherUser)
        ->put(route('posts.update', $post), [
            'content' => 'Malicious update attempt',
        ]);

    $response->assertStatus(403);
    $this->assertEquals('Original caption', $post->fresh()->content);
});

test('post content update requires non-empty string and obeys validation limits', function () {
    $user = User::factory()->create();

    $community = Community::create([
        'name' => 'Test Community',
        'slug' => 'test-community',
        'is_active' => true,
    ]);

    $post = Post::create([
        'user_id' => $user->id,
        'community_id' => $community->id,
        'content' => 'Original caption',
    ]);

    // Test required validation
    $responseEmpty = $this
        ->actingAs($user)
        ->put(route('posts.update', $post), [
            'content' => '',
        ]);
    $responseEmpty->assertSessionHasErrors('content');

    // Test max length validation (max:2000)
    $responseTooLong = $this
        ->actingAs($user)
        ->put(route('posts.update', $post), [
            'content' => str_repeat('a', 2001),
        ]);
    $responseTooLong->assertSessionHasErrors('content');
});
