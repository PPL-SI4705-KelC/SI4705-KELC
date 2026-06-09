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
        return $user->id === $blog->user_id && in_array($blog->status, [Blog::STATUS_DRAFT, Blog::STATUS_REJECTED]);
    }

    public function delete(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id && $blog->status === Blog::STATUS_DRAFT;
    }
}
