<?php

namespace Tests\Browser\AdminBlogTest;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;

abstract class AdminBlogTestBase extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Helper: create an admin user for tests.
     */
    protected function createAdmin(): User
    {
        return User::factory()->create([
            'name'     => 'Admin Dusk',
            'username' => 'admindusk' . time() . rand(100, 999),
            'email'    => 'admin_dusk_' . time() . rand(100, 999) . '@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);
    }

    /**
     * Helper: create a regular user for pending blog submissions.
     */
    protected function createRegularUser(): User
    {
        return User::factory()->create([
            'name'     => 'Regular User',
            'username' => 'regularuser' . time() . rand(100, 999),
            'email'    => 'user_dusk_' . time() . rand(100, 999) . '@example.com',
            'password' => bcrypt('password'),
            'role'     => 'user',
        ]);
    }

    /**
     * Helper: create a published blog owned by admin.
     */
    protected function createPublishedBlog(User $admin, string $title = 'Published Blog Test'): Blog
    {
        return Blog::create([
            'user_id'           => $admin->id,
            'title'             => $title,
            'short_description' => 'Short description for testing purposes.',
            'content'           => str_repeat('This is test content for the published blog post. ', 10),
            'category'          => 'Energy',
            'status'            => Blog::STATUS_PUBLISHED,
            'tags'              => 'test, energy, climate',
            'featured_image'    => 'blogs/test-image.jpg',
        ]);
    }

    /**
     * Helper: create a draft blog owned by admin.
     */
    protected function createDraftBlog(User $admin, string $title = 'Draft Blog Test'): Blog
    {
        return Blog::create([
            'user_id'           => $admin->id,
            'title'             => $title,
            'short_description' => 'Draft description for testing.',
            'content'           => str_repeat('Draft content placeholder text. ', 10),
            'category'          => 'Transportation',
            'status'            => Blog::STATUS_DRAFT,
            'tags'              => 'draft, test',
        ]);
    }

    /**
     * Helper: create a pending blog submitted by a regular user.
     */
    protected function createPendingBlog(User $user, string $title = 'Pending User Blog'): Blog
    {
        return Blog::create([
            'user_id'           => $user->id,
            'title'             => $title,
            'short_description' => 'Pending blog description by user.',
            'content'           => str_repeat('Pending blog content submitted by a regular user. ', 10),
            'category'          => 'Consumption',
            'status'            => Blog::STATUS_PENDING,
            'tags'              => 'pending, user',
        ]);
    }
}
