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
        $blogs = Blog::published()
            ->with('user:id,name,username,level')
            ->latest()
            ->paginate(12);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show single blog.
     */
    public function show(Blog $blog)
    {
        if ($blog->status !== 'published' && $blog->user_id !== Auth::id() && !Auth::user()?->isAdmin()) {
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
        $categories = Blog::categories();
        return view('blogs.create', compact('categories'));
    }

    /**
     * Store a new blog post.
     */
    public function store(StoreBlogRequest $request)
    {
        $user = Auth::user();

        $action = $request->input('action', 'pending');
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        $data['status'] = ($action === 'draft') ? Blog::STATUS_DRAFT : Blog::STATUS_PENDING;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        unset($data['action']);
        Blog::create($data);

        $msg = ($action === 'draft') ? 'Blog saved as draft!' : 'Blog post submitted for review!';

        return redirect()->route('blogs.my')
            ->with('success', $msg);
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
        $categories = Blog::categories();
        return view('blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update blog.
     */
    public function update(StoreBlogRequest $request, Blog $blog)
    {
        $this->authorize('update', $blog);

        $action = $request->input('action', 'pending');
        $data = $request->validated();

        if ($action === 'draft') {
            $data['status'] = Blog::STATUS_DRAFT;
        } else {
            $data['status'] = Blog::STATUS_PENDING;
            $data['reject_reason'] = null; // Clear if resubmitting
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        unset($data['action']);
        $blog->update($data);

        $msg = ($action === 'draft') ? 'Draft updated!' : 'Blog post resubmitted for review!';

        return redirect()->route('blogs.my')
            ->with('success', $msg);
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
