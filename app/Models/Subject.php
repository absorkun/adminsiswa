<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

}
