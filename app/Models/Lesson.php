<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;
    protected $table = "lessons";

    protected $fillable = ['phase_id', 'title', 'type', 'audio_url', 'image_url'];

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

}
