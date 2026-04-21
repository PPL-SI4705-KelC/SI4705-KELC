<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmissionRecord extends Model
{
    // Mendaftarkan kolom yang bisa diisi data
    protected $fillable = [
        'user_id',
        'activity_type',
        'amount_value',
        'carbon_impact',
        'recorded_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
