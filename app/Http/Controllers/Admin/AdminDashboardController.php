<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Community;
use App\Models\Emission;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
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
     * Delete multiple quizzes.
     */
    public function bulkDestroyQuizzes(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'No quiz questions selected for deletion.');
        }

        try {
            $quizzes = Quiz::whereIn('id', $ids)->get();
            $deletedCount = 0;
            foreach ($quizzes as $quiz) {
                $quiz->delete();
                $deletedCount++;
            }

            return redirect()
                ->route('admin.quizzes')
                ->with('success', "{$deletedCount} quiz questions deleted successfully.");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AdminDashboardController@bulkDestroyQuizzes failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete selected quiz questions.');
        }
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

        $users = $query->latest()->paginate(20)->withQueryString();
        return view('admin.users', compact('users'));
    }

    /**
     * Show form to create new user.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,user'],
            'xp' => ['required', 'integer', 'min:0'],
            'level' => ['required', 'integer', 'min:1'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'xp' => $request->xp,
            'level' => $request->level,
            'bio' => $request->bio,
            'total_point' => 0,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Show form to edit user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,user'],
            'xp' => ['required', 'integer', 'min:0'],
            'level' => ['required', 'integer', 'min:1'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'xp' => $request->xp,
            'level' => $request->level,
            'bio' => $request->bio,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Delete user.
     */
    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Delete multiple users.
     */
    public function bulkDestroyUsers(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'No users selected for deletion.');
        }

        // Exclude the current logged in user
        $ids = array_filter($ids, function($id) {
            return $id != Auth::id();
        });

        if (empty($ids)) {
            return back()->with('error', 'No valid users selected for deletion.');
        }

        try {
            $users = User::whereIn('id', $ids)->get();
            $deletedCount = 0;
            foreach ($users as $user) {
                if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $user->delete();
                $deletedCount++;
            }

            return redirect()
                ->route('admin.users')
                ->with('success', "{$deletedCount} users deleted successfully.");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AdminDashboardController@bulkDestroyUsers failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete selected users.');
        }
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
