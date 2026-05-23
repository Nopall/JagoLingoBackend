<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Phase extends Model
{
    use HasFactory;
    protected $table = "phases";

    protected $fillable = ['phase_title', 'description','course_id', 'package_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

}
