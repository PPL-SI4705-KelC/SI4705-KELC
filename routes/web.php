<?php

use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmissionController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

// ── Landing Page ─────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ── Authenticated User Routes ────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Carbon Footprint Calculator & Emissions
    Route::get('/emissions', [EmissionController::class, 'index'])->name('emissions.index');
    Route::get('/calculator', [EmissionController::class, 'create'])->name('calculator.create');
    Route::post('/calculator', [EmissionController::class, 'store'])->name('calculator.store');
    Route::get('/calculator/{emission}/result', [EmissionController::class, 'show'])->name('calculator.show');

    // Daily Quiz
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz', [QuizController::class, 'submit'])->name('quiz.submit');

    // Blogs
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/my', [BlogController::class, 'myBlogs'])->name('blogs.my');
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');

    // Community
    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::get('/community/{community}', [CommunityController::class, 'show'])->name('community.show');
    Route::get('/community/{community}/sidebar', [CommunityController::class, 'sidebarStatus'])->name('community.sidebar');
    Route::post('/community/{community}/join', [CommunityController::class, 'join'])->name('community.join');
    Route::post('/community/{community}/leave', [CommunityController::class, 'leave'])->name('community.leave');
    Route::post('/community/{community}/posts', [CommunityController::class, 'storePost'])->name('community.posts.store');
    Route::post('/posts/{post}/like', [CommunityController::class, 'toggleLike'])->name('posts.like');
    Route::delete('/posts/{post}', [CommunityController::class, 'destroyPost'])->name('posts.destroy');
    Route::put('/posts/{post}', [CommunityController::class, 'updatePost'])->name('posts.update');

    Route::post('/posts/{post}/comments', [CommunityController::class, 'storeComment'])->name('posts.comments.store');
    Route::delete('/comments/{comment}', [CommunityController::class, 'destroyComment'])->name('comments.destroy');
    Route::get('/community/{community}/hashtags', [CommunityController::class, 'hashtagSuggestions'])->name('community.hashtags');
    Route::get('/community/{community}/members', [CommunityController::class, 'memberSuggestions'])->name('community.members');

    // Smart Community Redirect (for chat button)
    Route::get('/community-redirect', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $joinedCommunity = \App\Models\Community::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        if ($joinedCommunity) {
            return redirect()->route('community.show', $joinedCommunity);
        }

        return redirect()->route('community.index');
    })->name('community.redirect');

    // Climate Journey Map
    Route::get('/journey', [JourneyController::class, 'index'])->name('journey.index');

    // Leaderboard
    Route::get('/leaderboard', [DashboardController::class, 'leaderboard'])->name('leaderboard');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});

// ── Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Admin Blogs (dedicated controller)
    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [AdminBlogController::class, 'store'])->name('blogs.store');
    Route::delete('/blogs/bulk-destroy', [AdminBlogController::class, 'bulkDestroy'])->name('blogs.bulk-destroy');
    Route::get('/blogs/{blog}/edit', [AdminBlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');
    Route::post('/blogs/{blog}/approve', [AdminBlogController::class, 'approve'])->name('blogs.approve');
    Route::post('/blogs/{blog}/reject', [AdminBlogController::class, 'reject'])->name('blogs.reject');
    
    // Admin Quizzes
    Route::get('/quizzes', [AdminDashboardController::class, 'quizzes'])->name('quizzes');
    Route::post('/quizzes', [AdminDashboardController::class, 'storeQuiz'])->name('quizzes.store');
    Route::delete('/quizzes/bulk-destroy', [AdminDashboardController::class, 'bulkDestroyQuizzes'])->name('quizzes.bulk-destroy');
    Route::get('/quizzes/{quiz}/edit', [AdminDashboardController::class, 'editQuiz'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [AdminDashboardController::class, 'updateQuiz'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [AdminDashboardController::class, 'destroyQuiz'])->name('quizzes.destroy');
    
    // Admin Communities
    Route::get('/communities', [AdminDashboardController::class, 'communities'])->name('communities');
    Route::get('/communities/create', [AdminDashboardController::class, 'createCommunity'])->name('communities.create');
    Route::post('/communities', [AdminDashboardController::class, 'storeCommunity'])->name('communities.store');
    Route::get('/communities/{community}/edit', [AdminDashboardController::class, 'editCommunity'])->name('communities.edit');
    Route::put('/communities/{community}', [AdminDashboardController::class, 'updateCommunity'])->name('communities.update');
    Route::delete('/communities/{community}', [AdminDashboardController::class, 'destroyCommunity'])->name('communities.destroy');
    Route::post('/communities/{community}/toggle-status', [AdminDashboardController::class, 'toggleCommunityStatus'])->name('communities.toggle-status');


    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminDashboardController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/bulk-destroy', [AdminDashboardController::class, 'bulkDestroyUsers'])->name('users.bulk-destroy');
    Route::get('/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/leaderboard', [AdminDashboardController::class, 'leaderboard'])->name('leaderboard');
});

require __DIR__.'/auth.php';
