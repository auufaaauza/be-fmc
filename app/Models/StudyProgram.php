<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyProgram extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'faculty',
        'description',
        'career_paths',
        'learning_path',
        'universities',
    ];

    protected $casts = [
        'career_paths' => 'array',
        'learning_path' => 'array',
        'universities' => 'array',
    ];

    public function criteria(): HasOne
    {
        return $this->hasOne(ProgramCriteria::class, 'program_id');
    }
}
