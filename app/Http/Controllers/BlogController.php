<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Models\Blog;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct(
        private GamificationService $gamificationService
    ) {}

    /**
     * List approved blogs.
     */
    public function index()
    {
        $blogs = Blog::approved()
            ->with('user:id,name,username,level')
            ->latest('published_at')
            ->paginate(12);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show single blog.
     */
    public function show(Blog $blog)
    {
        if ($blog->status !== 'approved' && $blog->user_id !== Auth::id()) {
            abort(404);
        }

        $blog->load('user:id,name,username,level,avatar');

        return view('blogs.show', compact('blog'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a new blog post.
     */
    public function store(StoreBlogRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('blogs.my')
            ->with('success', 'Blog post submitted for review!');
    }

    /**
     * Show user's own blogs.
     */
    public function myBlogs()
    {
        $blogs = Auth::user()->blogs()->latest()->paginate(10);
        return view('blogs.my', compact('blogs'));
    }

    /**
     * Edit blog (only pending/rejected).
     */
    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update blog.
     */
    public function update(StoreBlogRequest $request, Blog $blog)
    {
        $this->authorize('update', $blog);

        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }

        // Reset to pending if was rejected
        if ($blog->status === 'rejected') {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        $blog->update($data);

        return redirect()->route('blogs.my')
            ->with('success', 'Blog post updated!');
    }

    /**
     * Delete blog.
     */
    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);
        $blog->delete();

        return redirect()->route('blogs.my')
            ->with('success', 'Blog post deleted.');
    }
}
