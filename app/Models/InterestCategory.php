<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterestCategory extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'icon'];

    public function questions(): HasMany
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'category_id');
    }
}
