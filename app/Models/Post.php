<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            'likes_count' => 'integer',
            'comments_count' => 'integer',
        ];
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
        return $this->hasMany(Comment::class)
            ->whereNull('parent_comment_id')
            ->with(['user:id,name,username,avatar', 'replies']);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }



    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isVideo(): bool
    {
        if (!$this->image) {
            return false;
        }
        
        $extension = strtolower(pathinfo($this->image, PATHINFO_EXTENSION));
        return in_array($extension, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'm4v']);
    }
}
