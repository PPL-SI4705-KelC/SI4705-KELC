<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        return view('community.show', compact('community', 'posts', 'isMember', 'likedPostIds', 'savedPostIds'));
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
            'image'   => ['nullable', 'image', 'max:10240'],           // Photo only (up to 10 MB)
            'video'   => ['nullable', 'mimetypes:video/*', 'max:51200'], // Video only (up to 50 MB)
        ]);

        $data = [
            'user_id' => Auth::id(),
            'community_id' => $community->id,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
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
}
