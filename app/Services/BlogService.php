<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\User;

class BlogService
{
    private const FIRST_BLOG_XP = 1000;
    private const SUBSEQUENT_BLOG_XP = 500;

    public function __construct(
        private GamificationService $gamificationService
    ) {}

    /**
     * Award XP to the blog author upon approval.
     *
     * First published blog → 1000 XP, subsequent → 500 XP.
     *
     * @return int The amount of XP awarded.
     */
    public function awardBlogXp(Blog $blog): int
    {
        $author = User::findOrFail($blog->user_id);

        // Count how many published blogs the author now has (including this one)
        $publishedCount = Blog::where('user_id', $author->id)
            ->where('status', Blog::STATUS_PUBLISHED)
            ->count();

        // First published blog → bonus XP, subsequent → standard XP
        $xpAmount = ($publishedCount === 1)
            ? self::FIRST_BLOG_XP
            : self::SUBSEQUENT_BLOG_XP;

        // Award XP (handles XpLog and level ups)
        $this->gamificationService->awardXp(
            $author,
            $xpAmount,
            'blog',
            'Published a blog: ' . $blog->title
        );

        // Also increment total_point for legacy tracking
        $author->increment('total_point', $xpAmount);

        return $xpAmount;
    }
}
