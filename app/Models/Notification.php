<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    protected $table = "notification";

    protected $fillable = ['title', 'description', 'type'];

    protected $hidden = ['updated_at'];
}
