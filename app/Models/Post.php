<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'community_id',
        'content',
        'image',
        'likes_count',
        'comments_count',
    ];

    protected function casts(): array
    {
        return [
            'likes_count'    => 'integer',
            'comments_count' => 'integer',
        ];
    }

    // ── Accessors ─────────────────────────────────────────────

    /**
     * Content with #hashtags converted to clickable anchor tags.
     * Safe: e() escapes the original text; only the <a> tags are raw HTML.
     */
    public function getRenderedContentAttribute(): string
    {
        $escaped = e($this->content);
        // Replace #word (alphanumeric + underscore) with a styled link
        return preg_replace_callback(
            '/#(\w+)/',
            fn($m) => '<a href="?hashtag=' . Str::slug($m[1]) . '"
                          class="inline-block text-[#2D5A4C] font-semibold hover:underline"
                        >#' . e($m[1]) . '</a>',
            $escaped
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }

    public function saves(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_saves')->withTimestamps();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class)->latest();
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtags');
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isSavedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->saves()->where('user_id', $user->id)->exists();
    }
}
