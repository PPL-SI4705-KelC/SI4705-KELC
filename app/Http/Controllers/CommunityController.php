<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class CommunityController extends Controller
{
    /**
     * List communities.
     */
    public function index()
    {
        $user = Auth::user();
        $communities = Community::active()
            ->withCount('members')
            ->latest()
            ->paginate(12);

        $myCommunities = $user->communities()->pluck('communities.id')->toArray();

        return view('community.index', compact('communities', 'myCommunities'));
    }

    /**
     * Show community with posts feed.
     */
    public function show(Community $community)
    {
        $user = Auth::user();
        $isMember = $community->members()->where('user_id', $user->id)->exists();

        $posts = $community->posts()
            ->with(['user:id,name,username,level,avatar', 'comments.user:id,name,username'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(15);

        // Mark liked/saved for current user
        $likedPostIds = $user->likedPosts()->pluck('posts.id')->toArray();
        $savedPostIds = $user->savedPosts()->pluck('posts.id')->toArray();

        // Right sidebar data for the community
        $onlineMembers = $community->members()
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->get();

        $allMembers = $community->members()
            ->orderByDesc('users.last_seen_at')
            ->orderBy('users.name')
            ->get();

        return view('community.show', compact('community', 'posts', 'isMember', 'likedPostIds', 'savedPostIds', 'onlineMembers', 'allMembers'));
    }

    /**
     * Join a community.
     */
    public function join(Community $community)
    {
        $user = Auth::user();

        if (!$community->members()->where('user_id', $user->id)->exists()) {
            $community->members()->attach($user->id, ['role' => 'member']);
            $community->increment('member_count');

            // Send notification
            app(NotificationService::class)->notifyCommunityJoined($user, $community);
        }

        return back()->with('success', 'You joined ' . $community->name . '!');
    }

    /**
     * Leave a community.
     */
    public function leave(Community $community)
    {
        $user = Auth::user();

        if ($community->members()->where('user_id', $user->id)->exists()) {
            $community->members()->detach($user->id);
            $community->decrement('member_count');
        }

        return back()->with('success', 'You left ' . $community->name);
    }

    /**
     * Create a post in a community.
     */
    public function storePost(Request $request, Community $community)
    {
        $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:2000'],
            'image' => ['nullable', 'file', 'max:10240'], // Allow any file up to 10MB
            'file' => ['nullable', 'file', 'max:10240'], // Allow general files up to 10MB
        ]);

        $data = [
            'user_id' => Auth::id(),
            'community_id' => $community->id,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        } elseif ($request->hasFile('file')) {
            $data['image'] = $request->file('file')->store('posts', 'public');
        }

        Post::create($data);

        return back()->with('success', 'Post created!');
    }

    /**
     * Like/unlike a post.
     */
    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->detach($user->id);
            $post->decrement('likes_count');
        } else {
            $post->likes()->attach($user->id);
            $post->increment('likes_count');

            // Send notification (only on like, not unlike)
            app(NotificationService::class)->notifyPostLiked($post, $user);
        }

        return back();
    }

    /**
     * Save/unsave a post.
     */
    public function toggleSave(Post $post)
    {
        $user = Auth::user();

        if ($post->saves()->where('user_id', $user->id)->exists()) {
            $post->saves()->detach($user->id);
        } else {
            $post->saves()->attach($user->id);
        }

        return back();
    }

    /**
     * Add comment to a post.
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $request->content,
        ]);

        $post->increment('comments_count');

        // Send notification
        app(NotificationService::class)->notifyPostCommented($post, Auth::user());

        return back()->with('success', 'Comment added!');
    }

    /**
     * Delete a comment.
     */
    public function destroyComment(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $comment->post->decrement('comments_count');
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    /**
     * Get real-time sidebar status for a community.
     */
    public function sidebarStatus(Community $community)
    {
        $user = Auth::user();

        // Fetch online members
        $onlineMembers = $community->members()
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->get()
            ->map(function ($u) {
                return [
                    'username' => $u->username,
                    'name' => $u->name,
                    'avatar_url' => $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=E2E8F0&color=2A5C4D',
                ];
            });

        // Fetch all members (ordered by activity status first, then name)
        $allMembers = $community->members()
            ->orderByDesc('users.last_seen_at')
            ->orderBy('users.name')
            ->get()
            ->map(function ($u) {
                return [
                    'username' => $u->username,
                    'name' => $u->name,
                    'avatar_url' => $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=E2E8F0&color=2A5C4D',
                    'is_online' => $u->isOnline(),
                ];
            });

        return response()->json([
            'onlineMembers' => $onlineMembers,
            'allMembers' => $allMembers,
        ]);
    }
}
