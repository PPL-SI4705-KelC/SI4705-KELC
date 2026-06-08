<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Blog extends Model
{
    // ── Category Constants ────────────────────────────────────
    public const CATEGORY_TRANSPORTATION = 'Transportation';
    public const CATEGORY_CONSUMPTION    = 'Consumption';
    public const CATEGORY_ENERGY         = 'Energy';

    // ── Status Constants ─────────────────────────────────────
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_PENDING   = 'pending';
    public const STATUS_REJECTED  = 'rejected';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_description',
        'content',
        'category',
        'featured_image',
        'tags',
        'status',
        'reject_reason',
    ];

    // ── Boot: Auto-generate Slug ─────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Blog $blog): void {
            if (empty($blog->slug)) {
                $baseSlug = Str::slug($blog->title);
                $slug     = $baseSlug . '-' . Str::random(6);

                // Guarantee uniqueness
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . Str::random(6);
                }

                $blog->slug = $slug;
            }
        });
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ── Relationships ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ──────────────────────────────────────────────

    /**
     * Get all valid categories.
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_TRANSPORTATION,
            self::CATEGORY_CONSUMPTION,
            self::CATEGORY_ENERGY,
        ];
    }

    /**
     * Get all valid statuses.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
            self::STATUS_PENDING,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * Check if the blog is editable by admin.
     */
    public function isEditable(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_PUBLISHED]);
    }

    /**
     * Check if blog can be approved.
     */
    public function canBeApproved(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if blog can be rejected.
     */
    public function canBeRejected(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
