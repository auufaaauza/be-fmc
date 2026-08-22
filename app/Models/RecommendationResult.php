<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationResult extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'recommendation_id',
        'program_id',
        'primary_score',
        'secondary_score',
        'interest_score',
        'normalized_primary',
        'normalized_secondary',
        'normalized_interest',
        'preference_value',
        'rank_position',
    ];

    public function recommendation(): BelongsTo
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
