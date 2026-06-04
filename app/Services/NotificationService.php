<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Community;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;

class NotificationService
{
    /**
     * Notify blog author that their blog has been approved.
     */
    public function notifyBlogApproved(Blog $blog): void
    {
        Notification::create([
            'user_id' => $blog->user_id,
            'type'    => 'blog_approved',
            'title'   => 'Blog Approved',
            'message' => 'Your blog "' . $blog->title . '" has been approved and published!',
            'icon'    => '✅',
            'link'    => route('blogs.show', $blog),
        ]);
    }

    /**
     * Notify user that they earned XP.
     */
    public function notifyXpEarned(User $user, int $amount, string $source): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'xp_earned',
            'title'   => 'XP Earned',
            'message' => 'Congratulations! You earned ' . number_format($amount) . ' XP for your published blog.',
            'icon'    => '⭐',
            'link'    => route('journey.index'),
        ]);
    }

    /**
     * Notify user that they joined a community.
     */
    public function notifyCommunityJoined(User $user, Community $community): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'community_joined',
            'title'   => 'Joined Community',
            'message' => 'You have joined "' . $community->name . '". Welcome!',
            'icon'    => '👥',
            'link'    => route('community.show', $community),
        ]);
    }

    /**
     * Notify post author that someone liked their post.
     * Skips if the liker is the post author (self-like).
     */
    public function notifyPostLiked(Post $post, User $liker): void
    {
        // Don't notify self-likes
        if ($post->user_id === $liker->id) {
            return;
        }

        $communityName = $post->community?->name ?? 'a community';

        Notification::create([
            'user_id' => $post->user_id,
            'type'    => 'post_liked',
            'title'   => 'Post Liked',
            'message' => $liker->name . ' liked your post in "' . $communityName . '".',
            'icon'    => '❤️',
            'link'    => $post->community_id ? route('community.show', $post->community_id) : null,
        ]);
    }

    /**
     * Notify post author that someone commented on their post.
     * Skips if the commenter is the post author (self-comment).
     */
    public function notifyPostCommented(Post $post, User $commenter): void
    {
        // Don't notify self-comments
        if ($post->user_id === $commenter->id) {
            return;
        }

        $communityName = $post->community?->name ?? 'a community';

        Notification::create([
            'user_id' => $post->user_id,
            'type'    => 'post_commented',
            'title'   => 'New Comment',
            'message' => $commenter->name . ' commented on your post in "' . $communityName . '".',
            'icon'    => '💬',
            'link'    => $post->community_id ? route('community.show', $post->community_id) : null,
        ]);
    }

    /**
     * Notify a user that they were @mentioned in a comment.
     * Skips if the mentioner is the mentioned user.
     */
    public function notifyMentioned(User $mentioned, User $mentioner, Comment $comment, Post $post): void
    {
        if ($mentioned->id === $mentioner->id) {
            return;
        }

        $excerpt = mb_strlen($comment->content) > 60
            ? mb_substr($comment->content, 0, 60) . '…'
            : $comment->content;

        Notification::create([
            'user_id' => $mentioned->id,
            'type'    => 'mentioned',
            'title'   => 'You were mentioned',
            'message' => '@' . $mentioner->username . ' mentioned you: "' . $excerpt . '"',
            'icon'    => '🔔',
            'link'    => $post->community_id
                ? route('community.show', $post->community_id) . '#post-' . $post->id
                : null,
        ]);
    }
}
