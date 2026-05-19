<?php

namespace App\Models;

<<<<<<< HEAD
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
=======
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
<<<<<<< HEAD
=======
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
>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
<<<<<<< HEAD
        ];
    }
=======
            'xp' => 'integer',
            'level' => 'integer',
        ];
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

    public function savedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_saves')->withTimestamps();
    }
>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
}
