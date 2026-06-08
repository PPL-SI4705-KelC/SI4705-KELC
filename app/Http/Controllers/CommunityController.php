<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class CommunityController extends Controller
{
    /**
     * List communities.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Community::active()
            ->withCount('members');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $communities = $query->latest()
            ->paginate(12)
            ->withQueryString();

        $myCommunities = $user->communities()->pluck('communities.id')->toArray();

        return view('community.index', compact('communities', 'myCommunities'));
    }

    /**
     * Show community with posts feed.
     */
    public function show(Community $community)
    {
        $user = Auth::user();
        $isMember = request()->has('preview') ? false : $community->members()->where('user_id', $user->id)->exists();

        $posts = $community->posts()
            ->with(['user:id,name,username,level,avatar', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Mark liked for current user
        $likedPostIds = $user->likedPosts()->pluck('posts.id')->toArray();

        // Right sidebar data for the community
        $onlineMembers = $community->members()
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->get();

        $allMembers = $community->members()
            ->orderByDesc('users.last_seen_at')
            ->orderBy('users.name')
            ->get();

        return view('community.show', compact('community', 'posts', 'isMember', 'likedPostIds', 'onlineMembers', 'allMembers'));
    }

    /**
     * Join a community.
     */
    public function join(Community $community)
    {
        $user = Auth::user();

        if ($user->isAdmin() || request()->has('preview')) {
            return back()->with('error', 'Admins/previews cannot join communities.');
        }

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
            'image'   => ['nullable', 'image', 'max:10240'],                      // Photo only (up to 10 MB)
            'video'   => ['nullable', 'mimes:mp4,webm,ogg,mov,avi,mkv,3gp', 'max:51200'], // Video only (up to 50 MB)
        ]);

        $data = [
            'user_id'      => Auth::id(),
            'community_id' => $community->id,
            'content'      => $request->content,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        } elseif ($request->hasFile('video')) {
            $data['image'] = $request->file('video')->store('posts/videos', 'public');
        }

        Post::create($data);

        return back()->with('success', 'Post created!');
    }

    /**
     * Delete a post.
     */
    public function destroyPost(Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Delete associated file if it exists
        if ($post->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Post deleted.');
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
     * Add comment (or reply) to a post.
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content'           => ['required', 'string', 'min:1', 'max:500'],
            'parent_comment_id' => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        $comment = Comment::create([
            'user_id'           => Auth::id(),
            'post_id'           => $post->id,
            'parent_comment_id' => $request->parent_comment_id ?: null,
            'content'           => $request->content,
        ]);

        // Only count top-level comments
        if (!$comment->parent_comment_id) {
            $post->increment('comments_count');
        }

        $notif = app(NotificationService::class);

        // Notify post author of new comment (skip self-comment)
        $notif->notifyPostCommented($post, Auth::user());

        // Parse @mentions and notify each mentioned user
        if (preg_match_all('/@([\w]+)/u', $request->content, $matches)) {
            $mentionedUsernames = array_unique($matches[1]);
            $mentioner = Auth::user();

            foreach ($mentionedUsernames as $username) {
                $mentioned = User::where('username', $username)->first();
                if ($mentioned && $mentioned->id !== $mentioner->id) {
                    $notif->notifyMentioned($mentioned, $mentioner, $comment, $post);
                }
            }
        }

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
     * Return hashtag suggestions matching a query (JSON).
     */
    public function hashtagSuggestions(Request $request, Community $community)
    {
        $q = ltrim($request->get('q', ''), '#');
        $hashtags = Hashtag::when($q, fn($query) => $query->search($q))
            ->popular()
            ->limit(8)
            ->get(['name', 'slug', 'usage_count']);

        return response()->json($hashtags->map(fn($h) => [
            'name'        => $h->name,
            'slug'        => $h->slug,
            'usage_count' => $h->usage_count,
        ]));
    }

    /**
     * Return community member suggestions matching a query (JSON).
     */
    public function memberSuggestions(Request $request, Community $community)
    {
        $q = ltrim($request->get('q', ''), '@');
        $members = $community->members()
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('users.username', 'like', $q . '%')
                    ->orWhere('users.name', 'like', $q . '%');
            }))
            ->orderBy('users.username')
            ->limit(8)
            ->get(['users.id', 'users.name', 'users.username', 'users.avatar']);

        return response()->json($members->map(fn($u) => [
            'username'   => $u->username,
            'name'       => $u->name,
            'avatar_url' => $u->avatar
                ? asset('storage/' . $u->avatar)
                : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=E2E8F0&color=2A5C4D',
        ]));
    }

    /**
     * Get real-time sidebar status for a community.
     */
    public function sidebarStatus(Community $community)
    {
        $onlineMembers = $community->members()
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->get()
            ->map(fn($u) => [
                'username'   => $u->username,
                'name'       => $u->name,
                'avatar_url' => $u->avatar
                    ? asset('storage/' . $u->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=E2E8F0&color=2A5C4D',
            ]);

        $allMembers = $community->members()
            ->orderByDesc('users.last_seen_at')
            ->orderBy('users.name')
            ->get()
            ->map(fn($u) => [
                'username'   => $u->username,
                'name'       => $u->name,
                'avatar_url' => $u->avatar
                    ? asset('storage/' . $u->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=E2E8F0&color=2A5C4D',
                'is_online'  => $u->isOnline(),
            ]);

        return response()->json([
            'onlineMembers' => $onlineMembers,
            'allMembers'    => $allMembers,
        ]);
    }
}
