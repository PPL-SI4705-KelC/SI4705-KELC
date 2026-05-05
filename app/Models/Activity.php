<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'activity_date',
        'transport_data',
        'consumption_data',
        'energy_data',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'transport_data' => 'array',
            'consumption_data' => 'array',
            'energy_data' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function emission(): HasOne
    {
        return $this->hasOne(Emission::class);
    }
}
