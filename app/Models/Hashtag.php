<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hashtag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'usage_count' => 'integer',
        ];
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_hashtags');
    }

    /**
     * Scope: return most-used hashtags first.
     */
    public function scopePopular($query)
    {
        return $query->orderByDesc('usage_count');
    }

    /**
     * Scope: search by partial name.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where('name', 'like', $term . '%');
    }
}
