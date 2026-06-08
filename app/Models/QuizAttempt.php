<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'attempt_date',
        'question_ids',
        'answers',
        'correct_count',
        'xp_earned',
    ];

    protected function casts(): array
    {
        return [
            'attempt_date' => 'date',
            'question_ids' => 'array',
            'answers' => 'array',
            'correct_count' => 'integer',
            'xp_earned' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
