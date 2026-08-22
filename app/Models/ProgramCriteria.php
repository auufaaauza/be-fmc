<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramCriteria extends Model
{
    public $timestamps = false;

    protected $table = 'program_criteria';

    protected $fillable = [
        'program_id',
        'primary_subject_id',
        'primary_weight',
        'secondary_subject_id',
        'secondary_weight',
        'interest_category_id',
        'interest_weight',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'program_id');
    }

    public function primarySubject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'primary_subject_id');
    }

    public function secondarySubject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'secondary_subject_id');
    }

    public function interestCategory(): BelongsTo
    {
        return $this->belongsTo(InterestCategory::class, 'interest_category_id');
    }
}
