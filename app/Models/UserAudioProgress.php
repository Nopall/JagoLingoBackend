<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAudioProgress extends Model
{
    use HasFactory;

    protected $table = 'user_audio_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'total_listened_time',
    ];
}
