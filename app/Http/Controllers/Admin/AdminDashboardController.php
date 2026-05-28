<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Community;
use App\Models\Emission;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Admin overview dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_activities' => \App\Models\Activity::count(),
            'total_emissions' => Emission::sum('total_emission'),
            'avg_emission' => round(Emission::avg('total_emission') ?? 0, 2),
            'total_quizzes_taken' => QuizAttempt::count(),
            'total_blogs' => Blog::count(),
            'pending_blogs' => Blog::pending()->count(),
            'total_communities' => Community::count(),
        ];

        // Recent users
        $recentUsers = User::latest()->limit(4)->get();

        // Weekly emission trend
        $weeklyEmissions = Emission::selectRaw('DATE(emission_date) as date, AVG(total_emission) as avg_emission')
            ->where('emission_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'weeklyEmissions'));
    }

    /**
     * Manage blogs (approve/reject).
     */
    public function blogs(Request $request)
    {
        $search = $request->get('search');
        
        // Handle backwards compatibility for redirect parameters
        $tab = $request->get('tab');
        if (!$tab && $request->has('status')) {
            $status = $request->get('status');
            if ($status === 'approved') {
                $tab = 'published';
            } elseif ($status === 'draft') {
                $tab = 'draft';
            } else {
                $tab = 'all';
            }
        }
        $tab = $tab ?? 'all';
        
        $query = Blog::query();
        
        if ($tab === 'published') {
            $query->where('status', 'approved');
        } elseif ($tab === 'draft') {
            $query->where('status', 'draft')
                  ->whereHas('user', function($q) {
                      $q->where('role', 'admin');
                  });
        } else {
            // tab === 'all'
            $query->where(function($q) {
                $q->where('status', 'approved')
                  ->orWhere(function($sq) {
                      $sq->where('status', 'draft')
                         ->whereHas('user', function($uq) {
                             $uq->where('role', 'admin');
                         });
                  });
            });
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        $blogs = $query->latest()->paginate(5)->withQueryString();
        
        // Calculate counts for badges to match mockup numbers
        $allCount = Blog::count();
        $publishedCount = Blog::where('status', 'approved')->count();
        $draftCount = Blog::where('status', 'draft')->count();
        
        // Pending User Submissions (bottom block)
        $pendingQuery = Blog::where('status', 'pending')
            ->whereHas('user', function($q) {
                $q->where('role', 'user');
            })->with('user:id,name,username,bio');
            
        $pendingBlogs = $pendingQuery->latest()->get();
        $pendingCount = $pendingBlogs->count();
        
        return view('admin.blogs', compact(
            'blogs', 
            'tab', 
            'allCount', 
            'publishedCount', 
            'draftCount', 
            'pendingBlogs', 
            'pendingCount',
            'search'
        ));
    }

    /**
     * Show form to create new blog.
     */
    public function createBlog()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store admin created blog.
     */
    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:50'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->only('title', 'excerpt', 'content');
        $data['user_id'] = Auth::id();
        $data['slug'] = \Illuminate\Support\Str::slug($data['title']) . '-' . \Illuminate\Support\Str::random(6);
        $data['status'] = 'approved';
        $data['reviewed_by'] = Auth::id();
        $data['reviewed_at'] = now();
        $data['published_at'] = now();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs', ['status' => 'approved'])->with('success', 'Climate article published successfully!');
    }

    /**
     * Edit blog article.
     */
    public function editBlog(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update blog article.
     */
    public function updateBlog(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:50'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'rejection_reason' => ['nullable', 'required_if:status,rejected', 'string', 'max:500'],
        ]);

        $data = $request->only('title', 'excerpt', 'content', 'status', 'rejection_reason');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }

        $oldStatus = $blog->status;
        if ($data['status'] === 'approved' && $oldStatus !== 'approved') {
            $data['reviewed_by'] = Auth::id();
            $data['reviewed_at'] = now();
            $data['published_at'] = now();
            $data['rejection_reason'] = null;
        } elseif ($data['status'] === 'rejected' && $oldStatus !== 'rejected') {
            $data['reviewed_by'] = Auth::id();
            $data['reviewed_at'] = now();
            $data['published_at'] = null;
        }

        $blog->update($data);

        if ($data['status'] === 'approved' && $oldStatus !== 'approved') {
            $author = $blog->user;
            $isFirst = $author->blogs()->approved()->count() <= 1;
            $xpAmount = $isFirst ? 1000 : 500;

            $gamification = new GamificationService();
            $gamification->awardXp($author, $xpAmount, 'blog', $isFirst ? 'First blog published' : 'Blog published');
        }

        return redirect()->route('admin.blogs', ['status' => $data['status']])->with('success', 'Article updated successfully!');
    }

    /**
     * Delete blog article.
     */
    public function destroyBlog(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs', ['status' => 'approved'])->with('success', 'Article deleted.');
    }

    /**
     * Approve a blog.
     */
    public function approveBlog(Blog $blog)
    {
        $blog->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'published_at' => now(),
        ]);

        // Award XP to author
        $author = $blog->user;
        $isFirst = $author->blogs()->approved()->count() <= 1;
        $xpAmount = $isFirst ? 1000 : 500;

        $gamification = new GamificationService();
        $gamification->awardXp($author, $xpAmount, 'blog', $isFirst ? 'First blog published' : 'Blog published');

        return back()->with('success', 'Blog approved! Author awarded ' . $xpAmount . ' XP.');
    }

    /**
     * Reject a blog.
     */
    public function rejectBlog(Request $request, Blog $blog)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $blog->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Blog rejected.');
    }

    /**
     * Manage quizzes.
     */
    public function quizzes(Request $request)
    {
        $query = Quiz::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $quizzes = $query->latest()->paginate(20)->withQueryString();
        return view('admin.quizzes', compact('quizzes'));
    }

    /**
     * Store new quiz question.
     */
    public function storeQuiz(Request $request)
    {
        $request->validate([
            'question' => 'required|string|min:10',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
            'category' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        Quiz::create($request->only('question', 'options', 'correct_answer', 'category', 'difficulty'));

        return back()->with('success', 'Quiz question added!');
    }

    /**
     * Edit quiz question form.
     */
    public function editQuiz(Quiz $quiz)
    {
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Update quiz question.
     */
    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question' => 'required|string|min:10',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
            'category' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_active' => 'required|boolean',
        ]);

        $quiz->update($request->only('question', 'options', 'correct_answer', 'category', 'difficulty', 'is_active'));

        return redirect()->route('admin.quizzes')->with('success', 'Quiz question updated!');
    }

    /**
     * Delete quiz question.
     */
    public function destroyQuiz(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Quiz question deleted.');
    }

    /**
     * Manage users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Leaderboard management view.
     */
    public function leaderboard()
    {
        $leaderboard = User::where('role', 'user')
            ->orderBy('xp', 'desc')
            ->limit(50)
            ->get();

        return view('admin.leaderboard', compact('leaderboard'));
    }

    /**
     * Manage communities.
     */
    public function communities(Request $request)
    {
        $query = Community::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Sorting
        $sort = $request->get('sort', 'recent');
        if ($sort === 'popular') {
            $query->orderByDesc('member_count');
        } elseif ($sort === 'name') {
            $query->orderBy('name');
        } else {
            $query->latest();
        }

        $communities = $query->paginate(10)->withQueryString();

        // Calculate metrics
        $totalCommunities = Community::count();
        $totalMembers = Community::sum('member_count');
        
        if ($totalMembers >= 1000) {
            $formattedMembers = round($totalMembers / 1000, 1) . 'k';
        } else {
            $formattedMembers = $totalMembers;
        }

        return view('admin.communities.index', compact('communities', 'totalCommunities', 'formattedMembers', 'sort'));
    }

    /**
     * Show form to create new community.
     */
    public function createCommunity()
    {
        return view('admin.communities.create');
    }

    /**
     * Store new community.
     */
    public function storeCommunity(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:communities,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->only('name', 'description');
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . \Illuminate\Support\Str::random(6);
        $data['created_by'] = Auth::id();
        $data['is_active'] = true;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('communities', 'public');
        }

        $community = Community::create($data);
        
        $community->members()->attach(Auth::id(), ['role' => 'admin']);
        $community->increment('member_count');

        return redirect()->route('admin.communities')->with('success', 'Community created successfully!');
    }

    /**
     * Show form to edit community.
     */
    public function editCommunity(Community $community)
    {
        return view('admin.communities.edit', compact('community'));
    }

    /**
     * Update community.
     */
    public function updateCommunity(Request $request, Community $community)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:communities,name,' . $community->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ]);

        $data = $request->only('name', 'description', 'is_active');
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('communities', 'public');
        }

        $community->update($data);

        return redirect()->route('admin.communities')->with('success', 'Community updated successfully!');
    }

    /**
     * Delete community.
     */
    public function destroyCommunity(Community $community)
    {
        $community->delete();
        return redirect()->route('admin.communities')->with('success', 'Community deleted.');
    }

    /**
     * Toggle community status.
     */
    public function toggleCommunityStatus(Community $community)
    {
        $community->update(['is_active' => !$community->is_active]);
        return back()->with('success', 'Community status toggled!');
    }

    /**
     * Manage challenges.
     */
    public function challenges()
    {
        $challenges = [
            [
                'id' => 1,
                'title' => '7-Day Carbon Tracking Challenge',
                'description' => 'Track your carbon footprint daily for 7 days to earn bonus XP.',
                'category' => 'Carbon Footprint',
                'duration' => '7 Days',
                'xp_reward' => 500,
                'participants_count' => 125,
                'status' => 'Active',
            ],
            [
                'id' => 2,
                'title' => 'No Plastic Week',
                'description' => 'Avoid using single-use plastics for an entire week.',
                'category' => 'Waste Reduction',
                'duration' => '7 Days',
                'xp_reward' => 1000,
                'participants_count' => 84,
                'status' => 'Active',
            ],
            [
                'id' => 3,
                'title' => 'Commute Clean Challenge',
                'description' => 'Use public transport, bike, or walk instead of driving for 5 days.',
                'category' => 'Transport',
                'duration' => '5 Days',
                'xp_reward' => 750,
                'participants_count' => 98,
                'status' => 'Active',
            ],
        ];

        return view('admin.challenges.index', compact('challenges'));
    }
}
