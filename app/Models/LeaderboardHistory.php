<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardHistory extends Model
{
    use HasFactory;

    protected $table = 'leaderboard_histories';

    protected $fillable = [
        'user_id',
        'year_month',
        'xp',
    ];

    /**
     * Get the user that owns the history record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
