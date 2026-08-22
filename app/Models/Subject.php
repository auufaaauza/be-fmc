<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'code'];

    public function scores(): HasMany
    {
        return $this->hasMany(StudentScore::class);
    }
}
