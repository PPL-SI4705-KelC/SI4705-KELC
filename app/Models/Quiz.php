<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'question',
        'options',
        'correct_answer',
        'category',
        'difficulty',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'correct_answer' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
