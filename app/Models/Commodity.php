<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commodity extends Model
{
    use HasFactory;
    protected $table = "commodity";

    protected $fillable = ['name', 'image'];

    protected $hidden = ['created_at', 'updated_at'];
}
