<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emission extends Model
{
    protected $fillable = [
        'user_id',
        'activity_id',
        'transport_emission',
        'consumption_emission',
        'energy_emission',
        'total_emission',
        'sdg_score',
        'emission_date',
        'raw_input',
    ];

    protected function casts(): array
    {
        return [
            'transport_emission' => 'decimal:4',
            'consumption_emission' => 'decimal:4',
            'energy_emission' => 'decimal:4',
            'total_emission' => 'decimal:4',
            'sdg_score' => 'decimal:2',
            'emission_date' => 'date',
            'raw_input' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
