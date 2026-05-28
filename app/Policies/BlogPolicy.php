<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function update(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id && $blog->status !== 'approved';
    }

    public function delete(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id || $user->isAdmin();
    }
}
