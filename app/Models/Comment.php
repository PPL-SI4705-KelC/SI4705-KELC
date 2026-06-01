<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'parent_comment_id',
        'content',
    ];

    // ── Relations ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * The comment this is a reply to (null for top-level).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    /**
     * Direct replies to this comment.
     * Eager-loads up to 2 levels deep so the Blade template can render a
     * full 2-level thread without N+1 queries.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_comment_id')
                    ->with(['user:id,name,username,avatar', 'replies.user:id,name,username,avatar'])
                    ->latest();
    }

    // ── Helpers ──────────────────────────────────────────────

    /**
     * Is this comment a top-level comment (not a reply)?
     */
    public function isTopLevel(): bool
    {
        return is_null($this->parent_comment_id);
    }

    /**
     * Depth level: 0 = top-level, 1 = reply, 2 = reply-to-reply.
     * Used to cap nesting in the UI.
     */
    public function depth(): int
    {
        if (is_null($this->parent_comment_id)) return 0;
        if (is_null(optional($this->parent)->parent_comment_id)) return 1;
        return 2;
    }
}
