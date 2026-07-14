<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'xp',
        'level',
        'avatar',
        'bio',
        'total_point',
        'telp',
        'city',
        'postal_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'xp' => 'integer',
            'level' => 'integer',
            'total_point' => 'integer',
            'last_seen_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->role === 'user') {
                $user->leaderboard()->firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'total_xp' => $user->xp ?? 0,
                        'monthly_xp' => $user->xp ?? 0,
                    ]
                );
            }
        });

        static::updated(function ($user) {
            if ($user->role === 'user' && $user->isDirty('xp')) {
                $originalXp = $user->getOriginal('xp') ?? 0;
                $newXp = $user->xp ?? 0;
                $difference = $newXp - $originalXp;

                if ($difference != 0) {
                    $leaderboard = $user->leaderboard()->firstOrCreate(
                        ['user_id' => $user->id],
                        ['total_xp' => 0, 'monthly_xp' => 0]
                    );

                    $leaderboard->increment('total_xp', $difference);
                    $leaderboard->increment('monthly_xp', $difference);

                    // Also increment the history record for the current month
                    $currentMonth = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m');
                    $history = \App\Models\LeaderboardHistory::firstOrCreate(
                        ['user_id' => $user->id, 'year_month' => $currentMonth],
                        ['xp' => 0]
                    );
                    $history->increment('xp', $difference);
                }

            }
        });
    }

    // ── Role Helpers ─────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // ── Climate Journey Level Name ──────────────────────────

    public function getJourneyTitleAttribute(): string
    {
        return match (true) {
            $this->level >= 6 => 'Planet Guardian',
            $this->level >= 5 => 'Climate Champion',
            $this->level >= 4 => 'Climate Advocate',
            $this->level >= 3 => 'Eco Warrior',
            $this->level >= 2 => 'Green Starter',
            default => 'Eco Beginner',
        };
    }

    // ── User Activity Helper ────────────────────────────────

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    // ── Relationships ────────────────────────────────────────

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function emissions(): HasMany
    {
        return $this->hasMany(Emission::class);
    }

    public function xpLogs(): HasMany
    {
        return $this->hasMany(XpLog::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class)->withPivot('role')->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_likes')->withTimestamps();
    }



    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    public function leaderboard(): HasOne
    {
        return $this->hasOne(UserLeaderboard::class);
    }

    public function leaderboardHistories(): HasMany
    {
        return $this->hasMany(LeaderboardHistory::class);
    }
}

