<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Community;
use App\Models\Hashtag;
use App\Models\Post;
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
     * Supports optional ?hashtag=slug filter.
     */
    public function show(Community $community, Request $request)
    {
        $user          = Auth::user();
        $isMember      = $community->members()->where('user_id', $user->id)->exists();
        $activeHashtag = null;

        $postsQuery = $community->posts()
            ->with([
                'user:id,name,username,level,avatar',
                // Only load top-level comments; replies are nested inside
                'comments' => fn($q) => $q
                    ->whereNull('parent_comment_id')
                    ->with([
                        'user:id,name,username,avatar',
                        'replies' => fn($r) => $r
                            ->with([
                                'user:id,name,username,avatar',
                                'replies.user:id,name,username,avatar',
                            ])
                            ->latest(),
                    ])
                    ->latest(),
                'attachments',
                'hashtags',
            ])
            ->withCount(['likes', 'comments'])
            ->latest();

        // ── Hashtag filter ──────────────────────────────────────
        if ($request->filled('hashtag')) {
            $activeHashtag = Hashtag::where('slug', $request->hashtag)->first();
            if ($activeHashtag) {
                $postsQuery->whereHas('hashtags', fn($q) => $q->where('hashtag_id', $activeHashtag->id));
            }
        }

        $posts = $postsQuery->paginate(15)->withQueryString();

        // Mark liked/saved for current user
        $likedPostIds = $user->likedPosts()->pluck('posts.id')->toArray();
        $savedPostIds = $user->savedPosts()->pluck('posts.id')->toArray();

        // Right sidebar data
        $onlineUsers     = \App\Models\User::where('id', '!=', $user->id)->inRandomOrder()->limit(5)->get();
        $memberCommunity = $community->members()->where('users.id', '!=', $user->id)->inRandomOrder()->limit(4)->get();
        $friends         = \App\Models\User::where('id', '!=', $user->id)->inRandomOrder()->limit(3)->get();

        return view('community.show', compact(
            'community', 'posts', 'isMember',
            'likedPostIds', 'savedPostIds',
            'onlineUsers', 'memberCommunity', 'friends',
            'activeHashtag'
        ));
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
     * Create a post in a community (with optional image, files, hashtags).
     */
    public function storePost(Request $request, Community $community)
    {
        $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:2000'],
            'image'   => ['nullable', 'image', 'max:2048'],
            'files'   => ['nullable', 'array', 'max:5'],
            'files.*' => [
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt',
                'max:10240',
            ],
        ]);

        $data = [
            'user_id'      => Auth::id(),
            'community_id' => $community->id,
            'content'      => $request->content,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create($data);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store("post-files/{$post->id}", 'public');
                Attachment::create([
                    'post_id'   => $post->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // Extract & persist hashtags
        preg_match_all('/#(\w+)/', $request->content, $matches);
        $hashtagIds = [];
        foreach (array_unique($matches[1]) as $name) {
            $slug    = Str::slug($name);
            $hashtag = Hashtag::firstOrCreate(
                ['slug' => $slug],
                ['name' => strtolower($name), 'slug' => $slug]
            );
            $hashtag->increment('usage_count');
            $hashtagIds[] = $hashtag->id;
        }
        if ($hashtagIds) {
            $post->hashtags()->sync($hashtagIds);
        }

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
     * Add a top-level comment (traditional form POST).
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        Comment::create([
            'user_id'            => Auth::id(),
            'post_id'            => $post->id,
            'parent_comment_id'  => null,
            'content'            => $request->content,
        ]);

        $post->increment('comments_count');

        return back()->with('success', 'Comment added!');
    }

    /**
     * Add a reply comment via AJAX (JSON response).
     * POST /posts/{post}/comments/ajax
     *
     * Body: { content, parent_comment_id }
     */
    public function storeCommentAjax(Request $request, Post $post)
    {
        $request->validate([
            'content'            => ['required', 'string', 'min:1', 'max:500'],
            'parent_comment_id'  => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        $user = Auth::user();

        $comment = Comment::create([
            'user_id'            => $user->id,
            'post_id'            => $post->id,
            'parent_comment_id'  => $request->parent_comment_id ?: null,
            'content'            => $request->content,
        ]);

        $post->increment('comments_count');

        // Return enough data for Alpine.js to render the new reply immediately
        return response()->json([
            'comment' => [
                'id'                 => $comment->id,
                'content'            => $comment->content,
                'parent_comment_id'  => $comment->parent_comment_id,
                'created_at'         => $comment->created_at->diffForHumans(),
                'can_delete'         => true, // always true for own comment
                'delete_url'         => route('comments.destroy', $comment),
                'user' => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'name'     => $user->name,
                    'avatar'   => $user->avatar
                        ? asset('storage/' . $user->avatar)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=E2E8F0&color=2A5C4D&bold=true',
                ],
            ],
        ]);
    }

    /**
     * Delete a comment (also deletes all replies via CASCADE).
     */
    public function destroyComment(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $post = $comment->post;

        // Count this comment + all its nested replies before deleting
        $totalDeleted = 1 + $comment->replies()->count();
        $comment->delete();
        $post->decrement('comments_count', max(1, $totalDeleted));

        if (request()->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return back()->with('success', 'Comment deleted.');
    }

    /**
     * JSON endpoint: return up to 6 hashtag suggestions matching the query.
     * GET /hashtags/suggest?q=tech
     */
    public function hashtagSuggest(Request $request)
    {
        $q    = trim($request->get('q', ''));
        $tags = Hashtag::when(
                    $q !== '',
                    fn($query) => $query->search($q),
                    fn($query) => $query
                )
                ->popular()
                ->limit(6)
                ->get(['name', 'slug', 'usage_count']);

        return response()->json($tags);
    }
}
