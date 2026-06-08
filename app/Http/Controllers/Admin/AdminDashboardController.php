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
        $recentUsers = User::latest()->limit(5)->get();

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
        $status = $request->get('status', 'pending');
        $blogs = Blog::where('status', $status)
            ->with('user:id,name,username')
            ->latest()
            ->paginate(15);

        return view('admin.blogs', compact('blogs', 'status'));
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
    public function quizzes()
    {
        $quizzes = Quiz::latest()->paginate(20);
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
}
