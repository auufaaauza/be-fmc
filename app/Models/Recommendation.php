<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recommendation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'calculated_at',
        'counselor_notes',
        'counselor_id',
        'counselor_reviewed_at',
        'is_validated',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(RecommendationResult::class)->orderBy('rank_position');
    }
}
