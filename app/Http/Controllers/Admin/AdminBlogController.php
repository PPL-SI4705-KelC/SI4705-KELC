<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectBlogRequest;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Services\NotificationService;

class AdminBlogController extends Controller
{
    /**
     * Display blog management index.
     * Shows admin's own blogs (published/draft tabs) + pending user submissions.
     */
    public function index(Request $request): View
    {
        $tab    = $request->get('tab', 'all');
        $search = $request->get('search');

        // ── Admin's Own Blogs (Published + Draft tabs) ──────────
        $query = Blog::query()->with('user:id,name,username');

        // Filter by tab
        if ($tab === 'published') {
            $query->where('status', Blog::STATUS_PUBLISHED);
        } elseif ($tab === 'draft') {
            $query->where('status', Blog::STATUS_DRAFT)
                  ->where('user_id', Auth::id());
        } else {
            // "All" tab: show published + admin's own drafts
            $query->where(function ($q) {
                $q->where('status', Blog::STATUS_PUBLISHED)
                  ->orWhere(function ($sq) {
                      $sq->where('status', Blog::STATUS_DRAFT)
                         ->where('user_id', Auth::id());
                  });
            });
        }

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $blogs = $query->latest()->paginate(5)->withQueryString();

        // ── Count badges ────────────────────────────────────────
        $allCount = Blog::where(function ($q) {
            $q->where('status', Blog::STATUS_PUBLISHED)
              ->orWhere(function ($sq) {
                  $sq->where('status', Blog::STATUS_DRAFT)
                     ->where('user_id', Auth::id());
              });
        })->count();

        $publishedCount = Blog::where('status', Blog::STATUS_PUBLISHED)->count();

        $draftCount = Blog::where('status', Blog::STATUS_DRAFT)
            ->where('user_id', Auth::id())
            ->count();

        // ── Pending User Submissions ────────────────────────────
        $pendingBlogs = Blog::where('status', Blog::STATUS_PENDING)
            ->whereHas('user', fn ($q) => $q->where('role', 'user'))
            ->with('user:id,name,username,bio')
            ->latest()
            ->get();

        $pendingCount = $pendingBlogs->count();

        return view('admin.blogs', compact(
            'blogs',
            'tab',
            'search',
            'allCount',
            'publishedCount',
            'draftCount',
            'pendingBlogs',
            'pendingCount'
        ));
    }

    /**
     * Show create blog form.
     */
    public function create(): View
    {
        $categories = Blog::categories();
        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a new blog (publish or draft).
     */
    public function store(StoreBlogRequest $request): RedirectResponse
    {
        try {
            $action = $request->input('action', 'publish');
            $data   = $request->validated();

            $data['user_id'] = Auth::id();
            $data['status']  = ($action === 'draft') ? Blog::STATUS_DRAFT : Blog::STATUS_PUBLISHED;

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $data['featured_image'] = $request->file('featured_image')
                    ->store('blogs', 'public');
            }

            // Remove 'action' from data — it's not a column
            unset($data['action']);

            Blog::create($data);

            $message = ($action === 'draft')
                ? 'Blog saved as draft successfully!'
                : 'Blog published successfully!';

            $redirectTab = ($action === 'draft') ? 'draft' : 'published';

            return redirect()
                ->route('admin.blogs.index', ['tab' => $redirectTab])
                ->with('success', $message);

        } catch (\Throwable $e) {
            Log::error('AdminBlogController@store failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to save blog. Please try again.');
        }
    }

    /**
     * Show edit blog form.
     */
    public function edit(Blog $blog): View
    {
        $categories = Blog::categories();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update an existing blog (publish or save draft).
     */
    public function update(UpdateBlogRequest $request, Blog $blog): RedirectResponse
    {
        try {
            $action = $request->input('action', 'publish');
            $data   = $request->validated();

            $data['status'] = ($action === 'draft') ? Blog::STATUS_DRAFT : Blog::STATUS_PUBLISHED;

            // Handle featured image replacement
            if ($request->hasFile('featured_image')) {
                // Delete old image if it exists
                if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                    Storage::disk('public')->delete($blog->featured_image);
                }

                $data['featured_image'] = $request->file('featured_image')
                    ->store('blogs', 'public');
            }

            // Remove 'action' from data
            unset($data['action']);

            $blog->update($data);

            $message = ($action === 'draft')
                ? 'Draft updated successfully!'
                : 'Blog published successfully!';

            $redirectTab = ($action === 'draft') ? 'draft' : 'published';

            return redirect()
                ->route('admin.blogs.index', ['tab' => $redirectTab])
                ->with('success', $message);

        } catch (\Throwable $e) {
            Log::error('AdminBlogController@update failed: ' . $e->getMessage(), [
                'blog_id' => $blog->id,
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update blog. Please try again.');
        }
    }

    /**
     * Delete a blog and its featured image.
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        try {
            // Delete featured image from storage
            if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $blog->delete();

            return redirect()
                ->route('admin.blogs.index')
                ->with('success', 'Blog deleted successfully.');

        } catch (\Throwable $e) {
            Log::error('AdminBlogController@destroy failed: ' . $e->getMessage(), [
                'blog_id' => $blog->id,
            ]);

            return back()->with('error', 'Failed to delete blog.');
        }
    }

    /**
     * Approve a pending blog submission.
     *
     * CRITICAL: Uses DB::transaction to ensure atomicity.
     * XP Logic: First published blog = 1000 XP, subsequent = 500 XP.
     */
    public function approve(Blog $blog): RedirectResponse
    {
        // Guard: prevent approving a non-pending blog
        if (!$blog->canBeApproved()) {
            return back()->with('error', 'This blog cannot be approved. It is not in pending status.');
        }

        try {
            $xpAwarded = 0;

            DB::transaction(function () use ($blog, &$xpAwarded) {
                // 1. Change status to published
                $blog->update([
                    'status'        => Blog::STATUS_PUBLISHED,
                    'reject_reason' => null,
                ]);

                // 2. Award XP via BlogService
                $blogService = app(\App\Services\BlogService::class);
                $xpAwarded = $blogService->awardBlogXp($blog);
            });

            // 3. Send notifications to the blog author
            $notificationService = app(NotificationService::class);
            $notificationService->notifyBlogApproved($blog);
            $notificationService->notifyXpEarned(
                User::findOrFail($blog->user_id),
                $xpAwarded,
                'blog'
            );

            return back()->with('success', "Blog approved! Author awarded {$xpAwarded} XP.");

        } catch (\Throwable $e) {
            Log::error('AdminBlogController@approve failed: ' . $e->getMessage(), [
                'blog_id' => $blog->id,
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to approve blog. Transaction rolled back.');
        }
    }

    /**
     * Reject a pending blog submission.
     */
    public function reject(RejectBlogRequest $request, Blog $blog): RedirectResponse
    {
        // Guard: prevent rejecting a non-pending blog
        if (!$blog->canBeRejected()) {
            return back()->with('error', 'This blog cannot be rejected. It is not in pending status.');
        }

        try {
            $blog->update([
                'status'        => Blog::STATUS_REJECTED,
                'reject_reason' => $request->validated('reject_reason'),
            ]);

            return back()->with('success', 'Blog rejected. Author has been notified.');

        } catch (\Throwable $e) {
            Log::error('AdminBlogController@reject failed: ' . $e->getMessage(), [
                'blog_id' => $blog->id,
            ]);

            return back()->with('error', 'Failed to reject blog.');
        }
    }
}
